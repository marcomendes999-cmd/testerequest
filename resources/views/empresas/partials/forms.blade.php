{{-- campos básicos (nome, nif, etc) --}}
<div class="mb-3">
    <label class="form-label">Nome</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $empresa->nome ?? '') }}">
    @error('nome') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label class="form-label">NIF</label>
    <input type="text" name="nif" class="form-control" value="{{ old('nif', $empresa->nif ?? '') }}">
    @error('nif') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $empresa->email ?? '') }}">
    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Telefone</label>
    <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $empresa->telefone ?? '') }}">
    @error('telefone') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<hr>

<h5>Moradas</h5>
<p>Adiciona uma ou mais moradas. Tipo obrigatório: Rua, Avenida ou Travessa.</p>

<div id="moradas-wrapper">
    {{-- Renderiza moradas antigas (old input se houver) ou as moradas existentes da empresa --}}
    @php
        $oldMoradas = old('moradas', isset($empresa) ? $empresa->moradas->toArray() : []);
        if (empty($oldMoradas)) {
            // garante pelo menos um bloco vazio
            $oldMoradas = [['tipo'=>'Rua','rua'=>'','numero'=>'','cidade'=>'','codigo_postal'=>'']];
        }
    @endphp

    @foreach($oldMoradas as $i => $m)
    <div class="morada-block border rounded p-3 mb-2" data-index="{{ $i }}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>Morada #{{ $i + 1 }}</strong>
            <button type="button" class="btn btn-sm btn-danger remove-morada">Remover</button>
        </div>

        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Tipo</label>
                <select name="moradas[{{ $i }}][tipo]" class="form-select">
                    <option value="Rua" {{ ($m['tipo'] ?? '') == 'Rua' ? 'selected' : '' }}>Rua</option>
                    <option value="Avenida" {{ ($m['tipo'] ?? '') == 'Avenida' ? 'selected' : '' }}>Avenida</option>
                    <option value="Travessa" {{ ($m['tipo'] ?? '') == 'Travessa' ? 'selected' : '' }}>Travessa</option>
                </select>
                @error("moradas.$i.tipo") <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-5">
                <label class="form-label">Rua / Nome da via</label>
                <input type="text" name="moradas[{{ $i }}][rua]" class="form-control" value="{{ $m['rua'] ?? '' }}">
                @error("moradas.$i.rua") <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">Número</label>
                <input type="text" name="moradas[{{ $i }}][numero]" class="form-control" value="{{ $m['numero'] ?? '' }}">
                @error("moradas.$i.numero") <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mt-2">
                <label class="form-label">Cidade</label>
                <input type="text" name="moradas[{{ $i }}][cidade]" class="form-control" value="{{ $m['cidade'] ?? '' }}">
                @error("moradas.$i.cidade") <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mt-2">
                <label class="form-label">Código Postal</label>
                <input type="text" name="moradas[{{ $i }}][codigo_postal]" class="form-control" value="{{ $m['codigo_postal'] ?? '' }}">
                @error("moradas.$i.codigo_postal") <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>
    </div>
    @endforeach
</div>

<button type="button" id="add-morada" class="btn btn-sm btn-primary mb-3">+ Adicionar Morada</button>

<br>
<button type="submit" class="btn btn-success">Guardar</button>
<a href="{{ route('empresas.index') }}" class="btn btn-secondary">Cancelar</a>

{{-- Template escondido para clonar --}}
<template id="morada-template">
    <div class="morada-block border rounded p-3 mb-2" data-index="__INDEX__">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>Morada #__NUM__</strong>
            <button type="button" class="btn btn-sm btn-danger remove-morada">Remover</button>
        </div>

        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Tipo</label>
                <select name="moradas[__INDEX__][tipo]" class="form-select">
                    <option value="Rua">Rua</option>
                    <option value="Avenida">Avenida</option>
                    <option value="Travessa">Travessa</option>
                </select>
            </div>

            <div class="col-md-5">
                <label class="form-label">Rua / Nome da via</label>
                <input type="text" name="moradas[__INDEX__][rua]" class="form-control" value="">
            </div>

            <div class="col-md-2">
                <label class="form-label">Número</label>
                <input type="text" name="moradas[__INDEX__][numero]" class="form-control" value="">
            </div>

            <div class="col-md-4 mt-2">
                <label class="form-label">Cidade</label>
                <input type="text" name="moradas[__INDEX__][cidade]" class="form-control" value="">
            </div>

            <div class="col-md-4 mt-2">
                <label class="form-label">Código Postal</label>
                <input type="text" name="moradas[__INDEX__][codigo_postal]" class="form-control" value="">
            </div>
        </div>
    </div>
</template>

{{-- JS simples para clonar/remover --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('moradas-wrapper');
    const template = document.getElementById('morada-template').innerHTML;
    const addBtn = document.getElementById('add-morada');

    function reindexBlocks() {
        const blocks = wrapper.querySelectorAll('.morada-block');
        blocks.forEach((blk, idx) => {
            blk.setAttribute('data-index', idx);
            blk.querySelector('strong').textContent = 'Morada #' + (idx+1);
            // Atualizar todos os name attributes
            blk.querySelectorAll('[name]').forEach(el => {
                const name = el.getAttribute('name');
                // replace the first index occurrence
                const newName = name.replace(/moradas\[\d+\]/, 'moradas['+idx+']');
                el.setAttribute('name', newName);
            });
        });
    }

    addBtn.addEventListener('click', function () {
        const index = wrapper.querySelectorAll('.morada-block').length;
        let html = template.replace(/__INDEX__/g, index).replace(/__NUM__/g, index+1);
        wrapper.insertAdjacentHTML('beforeend', html);
        reindexBlocks();
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-morada')) {
            // remove bloco
            const block = e.target.closest('.morada-block');
            block.remove();
            reindexBlocks();
        }
    });
});
</script>
@endpush
