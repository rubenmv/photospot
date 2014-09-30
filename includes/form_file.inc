<div class="fGrupo">
    <label class="label1">Foto</label>
    <div class="controls">
        <button type="button" id="buttonFile" class="boton" value="Seleccionar" onclick="triggerFilePicker('inputFile');">Seleccionar</button>
        <input type="file" name="foto" id="inputFile" class="hidden <?php if(isset($required)) echo 'required'; ?>" onclick="resetError(this);" onchange="printSelectedFile(this.value);" />
    </div>
</div>