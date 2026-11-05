<?php
declare(strict_types=1);

echo "---- STARTED\n\n\n";

$page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT);
if ($page === null || $page === false || $page < 1) {
    $page = 1;
}

$crawler = new AnimeSamaCrawler();
$crawler->crawlCatalogue($page);

echo "---- ENDED\n\n\n";


class AnimeSamaCrawler
{
    private const BASE_URL    = 'https://anime-sama.to';
    private const SLEEP_DELAY = 2;
    private \CurlHandle $ch;

    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt_array($this->ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0',
            CURLOPT_TIMEOUT        => 15,
        ]);
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    // ------------------------------------------------------------------ //
    //  HTTP + DOM
    // ------------------------------------------------------------------ //

    private function fetch(string $url): \DOMXPath
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $html = curl_exec($this->ch);

        if ($html === false) {
            throw new \RuntimeException('cURL error: ' . curl_error($this->ch));
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        return new \DOMXPath($dom);
    }

    // ------------------------------------------------------------------ //
    //  Catalogue
    // ------------------------------------------------------------------ //

    public function crawlCatalogue(int $page): void
    {
        $url   = self::BASE_URL . '/catalogue/?page=' . $page;
        $xpath = $this->fetch($url);

        $links = $xpath->query('//div[@id="list_catalog"]//a');

        foreach ($links as $link) {
            $href = $link->getAttribute('href');

            if (!preg_match('/\/catalogue\/([^\/]+)/', $href, $m)) {
                continue;
            }

            $slug = $m[1];
            echo "URL : $slug\n- \n";

            try {
                $detailXpath = $this->fetch($href);
                $this->crawlPanel($detailXpath, $slug);
            } catch (\Throwable $e) {
                echo "ERREUR ($slug): " . $e->getMessage() . "\n";
            }

            sleep(self::SLEEP_DELAY);
        }
    }

    // ------------------------------------------------------------------ //
    //  Panel (detail page)
    // ------------------------------------------------------------------ //

    private function crawlPanel(\DOMXPath $xpath, string $channel): void
    {
        $buff = [
            'title'       => $this->queryText($xpath, '//h4[@id="titreOeuvre"]'),
            'description' => $this->queryText($xpath, '//p[
                contains(concat(" ",normalize-space(@class)," ")," text-sm ") and
                contains(concat(" ",normalize-space(@class)," ")," text-gray-300 ") and
                contains(concat(" ",normalize-space(@class)," ")," leading-relaxed ")
            ]'),
            'genres'      => $this->queryAllTexts($xpath, '//a[
                contains(concat(" ",normalize-space(@class)," ")," text-sm ") and
                contains(concat(" ",normalize-space(@class)," ")," text-gray-300 ")
            ]'),
            'data'        => $this->extractPanneaux($xpath),
        ];

        $this->upsert($channel, $buff);
    }

    // ------------------------------------------------------------------ //
    //  XPath helpers
    // ------------------------------------------------------------------ //

    private function queryText(\DOMXPath $xpath, string $query): string
    {
        $nodes = $xpath->query($query);
        return ($nodes && $nodes->length > 0)
            ? trim($nodes->item(0)->textContent)
            : 'N/A';
    }

    /** @return string[] */
    private function queryAllTexts(\DOMXPath $xpath, string $query): array
    {
        $nodes  = $xpath->query($query);
        $result = [];
        if ($nodes) {
            foreach ($nodes as $node) {
                $result[] = trim($node->textContent);
            }
        }
        return $result;
    }

    // ------------------------------------------------------------------ //
    //  Script parsing
    // ------------------------------------------------------------------ //

    /** @return array<int, array{name:string, url:string, saison:string, lang:string|null}> */
    private function extractPanneaux(\DOMXPath $xpath): array
    {
        $data    = [];
        $scripts = $xpath->query('//script');

        if (!$scripts) {
            return $data;
        }

        $pattern = '/panneauAnime\(\s*["\']([^"\']+)["\']\s*,\s*["\']([^"\']+)["\']\s*\)/';

        foreach ($scripts as $script) {
            if (!preg_match_all($pattern, $script->textContent, $matches, PREG_SET_ORDER)) {
                continue;
            }

            foreach ($matches as $match) {
                $parts = explode('/', $match[2]);
                if (!isset($parts[1])) {
                    continue;
                }
                $data[] = [
                    'name'   => $match[1],
                    'url'    => $match[2],
                    'saison' => $parts[0],
                    'lang'   => $parts[1],
                ];
            }
        }

        return $data;
    }

    // ------------------------------------------------------------------ //
    //  Database (upsert)
    // ------------------------------------------------------------------ //

    private function upsert(string $channel, array $buff): void
    {
        $json     = json_encode($buff, JSON_THROW_ON_ERROR);
        $taille   = count($buff['data']);

        $existing = __OmsySql
            ->execute('SELECT ID FROM as_crawler WHERE source = ?', [$channel])
            ->fetchAll();

        if (empty($existing)) {
            __OmsySql->execute(
                'INSERT INTO as_crawler (source, source_type, catalogue, data, taille)
                 VALUES (?, ?, ?, ?, ?)',
                [$channel, 'catalogue', $channel, $json, $taille]
            );
        } else {
            __OmsySql->execute(
                'UPDATE as_crawler SET data = ?, taille = ? WHERE ID = ?',
                [$json, $taille, $existing[0]['ID']]
            );
        }
    }
}