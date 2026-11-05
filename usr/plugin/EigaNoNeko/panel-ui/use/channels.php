<?php
omsp_header();
?>
<section class="kit-head">
    <div class="kit-grid-contenaire">
        <div class="information">
            <h2>Liste des catalogues</h2>
        </div>
    </div>
    <div class="kit-grid-contenaire">
        <ul>
            <li>
                <a href="/panel/getter?plugin=EigaNoNeko&page=channel">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M460-460H240v-40h220v-220h40v220h220v40H500v220h-40v-220Z"/></svg>
                    </span>
                    <span class="name">Créer un catalogue</span>
                </a>
            </li>
        </ul>
    </div>
</section>
<section class="kit-contenaire">
<?php
SQLX_pagetab($allCatalogue);
SQLX_table([
    "head" => [
        "Title" ,
        //'<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M698.46-160H296.92v-440l250.77-247.69L565.38-830q5.47 5.46 9.2 14 3.73 8.54 3.73 15.69v6.31l-40.93 194h278q25.08 0 44.85 19.77Q880-560.46 880-535.38v49.23q0 5.46-1.23 11.92t-3.23 11.92L766.31-203.38q-8.23 18.46-27.69 30.92Q719.15-160 698.46-160Zm-361.54-40h361.54q8.46 0 17.31-4.62 8.85-4.61 13.46-15.38L840-480v-55.38q0-10.77-6.92-17.7-6.93-6.92-17.7-6.92H487.69L534-778.46 336.92-582.92V-200Zm0-382.92V-200v-382.92Zm-40-17.08v40H160v360h136.92v40H120v-440h176.92Z"/></svg>',
        //'<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M261.54-767.69h401.54v440L412.31-80l-17.69-17.69q-5.47-5.46-9.2-14-3.73-8.54-3.73-15.69v-6.31l40.93-194h-278q-25.08 0-44.85-19.77Q80-367.23 80-392.31v-49.23q0-5.46 1.23-11.92t3.23-11.92l109.23-258.93q8.23-18.46 27.69-30.92 19.47-12.46 40.16-12.46Zm361.54 40H261.54q-8.46 0-17.31 4.61-8.85 4.62-13.46 15.39L120-447.69v55.38q0 10.77 6.92 17.69 6.93 6.93 17.7 6.93h327.69L426-149.23l197.08-195.54v-382.92Zm0 382.92v-382.92 382.92Zm40 17.08v-40H800v-360H663.08v-40H840v440H663.08Z"/></svg>',
        //'<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M240-180v-555.38q0-27.62 18.5-46.12Q277-800 304.62-800H520v40H304.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93V-242l200-86 200 86v-278h40v340L480-283.08 240-180Zm40-580h240-240Zm400 160v-80h-80v-40h80v-80h40v80h80v40h-80v80h-40Z"/></svg>'
    ],
    "body" => $buff
]);

SQLX_pagetab($allCatalogue);
?>
</section>
<?php
omsp_footer();
                