<section class="kit-table">
	<table class="">
		<tr class="">
			<td class="checkbox">
				<input type="checkbox" name="check_all" onchange="checkAllBox(this, 'action_box')">
			</td>
			<?php foreach ($table['head'] as $value): ?>
			<td class="<?php if(is_int($value)) echo 'icon-int'; ?>"><?= $value ?></td>
			<?php endforeach; ?>
			<td></td>
		</tr>
		<?php foreach ($table['body'] as $td): ?>
		<tr>
			<td class="checkbox">
				<input type="checkbox" name="check_all">
			</td>
			<?php foreach ($td as $content): ?>
			<td class="<?php if(is_int($content)) echo 'icon-int'; ?>"><?php if(!is_array($content)) echo $content; ?></td>
			<?php endforeach; ?>
			<td class="options">
				<ul class="action-table-list">
					<?php if(isset($td['omsyPanel.table.options']['active']) && (__OmsyPermission->can(__OmsyPermission->OPEN, __GETTER_PRM__))): ?>
					<li>
						<a href="?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= $td['omsyPanel.table.options']['active']['page'] ?>&active=<?= $td['omsyPanel.table.options']['active']['value'] ?>" class="title-stream">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M455.38-200h49.24v-78.62L640-414v-173.69q0-4.62-3.85-8.46-3.84-3.85-8.46-3.85H332.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46V-414l135.38 135.38V-200Zm-59.99 60v-113.08L260-388.46v-199.23q0-29.92 21.19-51.12Q302.39-660 332.31-660h44.61l-29.99 30v-190h59.99v160h146.16v-160h59.99v190l-29.99-30h44.61q29.92 0 51.12 21.19Q700-617.61 700-587.69v199.23L564.61-253.08V-140H395.39ZM480-400Z"/></svg>
						</a>
					</li>
					<?php endif; ?>
					<?php if(isset($td['omsyPanel.table.options']['edit']) && (__OmsyPermission->can(__OmsyPermission->OPEN, __GETTER_PRM__))): ?>
					<li>
						<a href="?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= $td['omsyPanel.table.options']['edit']['page'] ?>&edit=<?= $td['omsyPanel.table.options']['edit']['value'] ?>" class="title-stream">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M200-200h43.92l427.93-427.92-43.93-43.93L200-243.92V-200Zm-40 40v-100.77l527.23-527.77q6.15-5.48 13.57-8.47 7.43-2.99 15.49-2.99t15.62 2.54q7.55 2.54 13.94 9.15l42.69 42.93q6.61 6.38 9.04 14 2.42 7.63 2.42 15.25 0 8.13-2.74 15.56-2.74 7.42-8.72 13.57L260.77-160H160Zm600.77-556.31-44.46-44.46 44.46 44.46ZM649.5-649.5l-21.58-22.35 43.93 43.93-22.35-21.58Z"/></svg>
						</a>
					</li>
					<?php endif; ?>
					<?php if(isset($td['omsyPanel.table.options']['view'])): ?>
					<li>
						<a href="" class="title-stream">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M224.62-160q-27.62 0-46.12-18.5Q160-197 160-224.62V-360h40v135.38q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69H360v40H224.62Zm510.76 0H600v-40h135.38q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-360h40v135.38q0 27.62-18.5 46.12Q763-160 735.38-160ZM160-735.38q0-27.62 18.5-46.12Q197-800 224.62-800H360v40H224.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93V-600h-40v-135.38Zm640 0V-600h-40v-135.38q0-9.24-7.69-16.93-7.69-7.69-16.93-7.69H600v-40h135.38q27.62 0 46.12 18.5Q800-763 800-735.38ZM481.54-271.54q13.31 0 22.42-9.11 9.12-9.12 9.12-22.43 0-13.3-9.12-22.42-9.11-9.12-22.42-9.12-13.31 0-22.42 9.12-9.12 9.12-9.12 22.42 0 13.31 9.12 22.43 9.11 9.11 22.42 9.11Zm-20.62-132.23h38.39q1.54-25.54 9.92-42 8.39-16.46 32.31-40.38 30.38-30.39 41.88-51.12 11.5-20.73 11.5-46.11 0-46.31-31.3-75.7-31.31-29.38-80.54-29.38-39.23 0-69.46 19.84-30.24 19.85-47 57.08l36.76 15.46q10.85-25.23 31.5-40.19 20.66-14.96 46.66-14.96 32.84 0 53.81 18.96 20.96 18.96 20.96 49.58 0 18.46-9.12 34.81-9.11 16.34-31.34 36.11-29.93 28.23-42.43 52.15-12.5 23.93-12.5 55.85Z"/></svg>
						</a>
					</li>
					<?php endif; ?>
					<?php if(isset($td['omsyPanel.table.options']['view2'])): ?>
					<li>
						<a href="" class="title-stream">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M224.62-160q-27.62 0-46.12-18.5Q160-197 160-224.62V-360h40v135.38q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69H360v40H224.62Zm510.76 0H600v-40h135.38q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-360h40v135.38q0 27.62-18.5 46.12Q763-160 735.38-160ZM160-735.38q0-27.62 18.5-46.12Q197-800 224.62-800H360v40H224.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93V-600h-40v-135.38Zm640 0V-600h-40v-135.38q0-9.24-7.69-16.93-7.69-7.69-16.93-7.69H600v-40h135.38q27.62 0 46.12 18.5Q800-763 800-735.38ZM481.54-271.54q13.31 0 22.42-9.11 9.12-9.12 9.12-22.43 0-13.3-9.12-22.42-9.11-9.12-22.42-9.12-13.31 0-22.42 9.12-9.12 9.12-9.12 22.42 0 13.31 9.12 22.43 9.11 9.11 22.42 9.11Zm-20.62-132.23h38.39q1.54-25.54 9.92-42 8.39-16.46 32.31-40.38 30.38-30.39 41.88-51.12 11.5-20.73 11.5-46.11 0-46.31-31.3-75.7-31.31-29.38-80.54-29.38-39.23 0-69.46 19.84-30.24 19.85-47 57.08l36.76 15.46q10.85-25.23 31.5-40.19 20.66-14.96 46.66-14.96 32.84 0 53.81 18.96 20.96 18.96 20.96 49.58 0 18.46-9.12 34.81-9.11 16.34-31.34 36.11-29.93 28.23-42.43 52.15-12.5 23.93-12.5 55.85Z"/></svg>
						</a>
					</li>
					<?php endif; ?>
					<?php if(isset($td['omsyPanel.table.options']['copy'])): ?>
					<li>
						<a href="javascript:Omsy.copyString('<?= $td['omsyPanel.table.options']['copy']['value'] ?>')" class="title-stream">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M735.38-240H344.62q-27.62 0-46.12-18.5Q280-277 280-304.62v-510.76q0-27.62 18.5-46.12Q317-880 344.62-880H620l180 180v395.38q0 27.62-18.5 46.12Q763-240 735.38-240ZM600-680v-160H344.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93v510.76q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69h390.76q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-680H600ZM184.62-80Q157-80 138.5-98.5 120-117 120-144.62V-640h40v495.38q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69H560v40H184.62ZM320-840v160-160 560-560Z"/></svg>
						</a>
					</li>
					<?php endif; ?>
					<?php if(isset($td['omsyPanel.table.options']['remove']) && (__OmsyPermission->can(__OmsyPermission->DELETE, "00000007"))): ?>
					<li>
						<a href="?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= $td['omsyPanel.table.options']['remove']['page'] ?>&remove=<?= $td['omsyPanel.table.options']['remove']['value'] ?>" class="title-stream">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M304.62-160q-26.85 0-45.74-18.88Q240-197.77 240-224.62V-720h-40v-40h160v-30.77h240V-760h160v40h-40v495.38q0 27.62-18.5 46.12Q683-160 655.38-160H304.62ZM680-720H280v495.38q0 10.77 6.92 17.7 6.93 6.92 17.7 6.92h350.76q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-720ZM392.31-280h40v-360h-40v360Zm135.38 0h40v-360h-40v360ZM280-720v520-520Z"/></svg>
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</section>
<script>
const checkAllBox = (element, id) => {
    const allBox = document.querySelectorAll(`input.${id}`)
    console.log(allBox)

    let ch = false
    if(element.checked) ch = true

    allBox.forEach((e) => {
        e.checked = ch
    })
}
</script>