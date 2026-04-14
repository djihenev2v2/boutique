{{--
    Shared product form partial
    Variables expected:
      $product   — Product model (or null for create)
      $categories — Collection<Category>
      $attributes — Collection<ProductAttribute> with values
      $formAction — string route URL
      $formMethod — 'POST' | 'PATCH'
--}}

@php
    $isEdit = isset($product) && $product->exists;
    $editAttrValues = [];
    if ($isEdit) {
        foreach (($product->variants ?? collect()) as $variant) {
            foreach (($variant->attributeValues ?? collect()) as $av) {
                $editAttrValues[$av->attribute_id][] = $av->value;
            }
        }
        $editAttrValues = array_map(fn($vals) => array_values(array_unique($vals)), $editAttrValues);
    }
@endphp

<form method="POST"
      action="{{ $formAction }}"
      enctype="multipart/form-data"
      id="productForm"
      class="space-y-6">
    @csrf
    @if($formMethod === 'PATCH') @method('PATCH') @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
        <p class="text-[13px] font-bold text-red-600 mb-2">Erreurs de validation :</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li class="text-[12px] text-red-500">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ─── TWO-COLUMN LAYOUT ─── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ───── LEFT COLUMN (main) ───── --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Informations Générales --}}
            <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-6">
                <div class="flex items-center gap-2.5 mb-5">
                    <svg class="w-4 h-4 text-[#27467b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <h2 class="text-[15px] font-bold text-[#002352]">Informations Générales</h2>
                </div>

                <div class="space-y-4">
                    {{-- Nom --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-1.5">Nom du produit <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                               placeholder="Ex: Chemise en Lin Premium"
                               class="w-full text-[13.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-4 py-2.5 placeholder-[#747780]/70 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors @error('name') ring-2 ring-red-400 @enderror"/>
                        @error('name')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Catégorie + Marque --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-1.5">Catégorie</label>
                            <select name="category_id"
                                class="w-full text-[13.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors">
                                <option value="">— Sélectionner —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-1.5">Marque</label>
                            <input type="text" name="brand" value="{{ old('brand', $product->brand ?? '') }}"
                                   placeholder="Ex: Azure Elite"
                                   class="w-full text-[13.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-4 py-2.5 placeholder-[#747780]/70 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-1.5">Description</label>
                        <textarea name="description" rows="4"
                                  placeholder="Décrivez votre produit ici..."
                                  class="w-full text-[13.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-4 py-2.5 placeholder-[#747780]/70 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors resize-none">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Variantes --}}
            <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-6" id="variantsSection">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#27467b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                        <h2 class="text-[15px] font-bold text-[#002352]">Variantes</h2>
                    </div>
                    <button type="button" id="generateVariantsBtn"
                        class="inline-flex items-center gap-1.5 bg-[#002352] text-white text-[12px] font-semibold px-4 py-2 rounded-full hover:bg-[#18396e] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Générer les variantes
                    </button>
                </div>

                {{-- Attribute checkboxes --}}
                <div class="flex flex-wrap gap-3 mb-5" id="attributeCheckboxes">
                    @foreach($attributes as $attr)
                    <label class="flex items-center gap-2 cursor-pointer select-none group">
                        <input type="checkbox"
                               class="attribute-checkbox w-4 h-4 rounded accent-[#002352] cursor-pointer"
                               value="{{ $attr->id }}"
                               data-name="{{ $attr->name }}"
                               data-current-values="{{ isset($editAttrValues[$attr->id]) ? implode(',', $editAttrValues[$attr->id]) : '' }}"/>
                        <span class="text-[13px] font-medium text-[#43474f] group-hover:text-[#002352] transition-colors">{{ $attr->name }}</span>
                    </label>
                    @endforeach
                </div>

                {{-- Dynamic attribute value tags --}}
                <div id="attributeValueSections" class="space-y-3 mb-5"></div>

                {{-- Variants table --}}
                <div id="variantsTableWrapper" class="{{ $isEdit && $product->variants->isNotEmpty() ? '' : 'hidden' }}">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-[#747780] flex-1 min-w-[80px]">Variantes générées</p>
                        <div class="flex items-center gap-1.5">
                            <input type="number" id="bulkPriceInput" placeholder="Prix DA" min="0" step="1"
                                class="w-24 text-[12px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-lg px-2.5 py-1.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
                            <button type="button" id="applyPriceAll"
                                class="text-[11px] font-bold px-3 py-1.5 text-[#27467b] bg-[#f2f4f6] hover:bg-[#002352] hover:text-white rounded-lg transition-colors whitespace-nowrap">
                                → Prix à toutes
                            </button>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <input type="number" id="bulkStockInput" placeholder="Stock" min="0"
                                class="w-24 text-[12px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-lg px-2.5 py-1.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
                            <button type="button" id="applyStockAll"
                                class="text-[11px] font-bold px-3 py-1.5 text-[#27467b] bg-[#f2f4f6] hover:bg-[#002352] hover:text-white rounded-lg transition-colors whitespace-nowrap">
                                → Stock à toutes
                            </button>
                        </div>
                    </div>

                    <div class="rounded-xl overflow-hidden border border-[#edeef0]">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-[#f2f4f6]">
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Variante</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">SKU</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Prix (DA)</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Stock</th>
                                    <th class="px-4 py-3 w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="variantsTableBody" class="divide-y divide-[#f2f4f6]">
                                @if($isEdit)
                                    @foreach($product->variants as $variant)
                                    <tr class="variant-row hover:bg-[#f8f9fb] transition-colors">
                                        <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $variant->id }}">
                                        @foreach($variant->attributeValues as $av)
                                        <input type="hidden" name="variants[{{ $loop->index }}][attr_texts][{{ $av->attribute_id }}]" value="{{ $av->value }}">
                                        @endforeach
                                        <td class="px-4 py-3 text-[13px] font-medium text-[#191c1e]">{{ $variant->label }}</td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="variants[{{ $loop->index }}][sku]" value="{{ $variant->sku }}"
                                                class="w-28 text-[12.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="variants[{{ $loop->index }}][price]" value="{{ $variant->price }}" min="0" step="1"
                                                class="variant-price w-24 text-[12.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="variants[{{ $loop->index }}][stock]" value="{{ $variant->stock }}" min="0"
                                                class="variant-stock w-20 text-[12.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button type="button" class="remove-variant p-1 text-[#c4c6d1] hover:text-red-500 transition-colors rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ───── RIGHT COLUMN (sidebar) ───── --}}
        <div class="space-y-5">

            {{-- Status + Price --}}
            <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-5">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-[#616363]">Statut du produit</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                               {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <div class="w-10 h-6 bg-[#e1e2e4] rounded-full peer peer-checked:bg-[#002352] transition-colors after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                    </label>
                </div>

                <label class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-1.5">Prix de base (DA) <span class="text-red-500">*</span></label>
                <div class="flex items-stretch bg-[#f2f4f6] rounded-xl overflow-hidden @error('base_price') ring-2 ring-red-400 @enderror focus-within:ring-2 focus-within:ring-[#002352]/20 focus-within:bg-white transition-colors">
                    <input type="number" name="base_price" value="{{ old('base_price', $product->base_price ?? '') }}"
                           min="0" step="1" placeholder="0"
                           id="basePriceInput"
                           class="flex-1 min-w-0 text-[22px] font-bold text-[#002352] bg-transparent border-none outline-none px-4 py-2.5"/>
                    <span class="flex items-center px-3 text-[12px] font-bold text-[#747780] border-l border-[#e1e2e4] whitespace-nowrap">DZD</span>
                </div>
                @error('base_price')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror

                <div class="mt-3 flex items-start gap-2 bg-[#f2f4f6] rounded-xl p-3">
                    <svg class="w-4 h-4 text-[#27467b] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <p class="text-[11px] text-[#5d5f5f] leading-relaxed">Les prix des variantes écraseront le prix de base si définis.</p>
                </div>
            </div>

            {{-- Images --}}
            <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-5">
                <div class="flex items-center gap-2.5 mb-4">
                    <svg class="w-4 h-4 text-[#27467b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                    <h3 class="text-[14px] font-bold text-[#002352]">Images</h3>
                </div>

                {{-- Existing images --}}
                @if($isEdit && $product->images->isNotEmpty())
                <div class="grid grid-cols-3 gap-2 mb-3" id="existingImages">
                    @foreach($product->images as $img)
                    <div class="relative rounded-xl overflow-hidden bg-[#f2f4f6] aspect-square" data-image-id="{{ $img->id }}">
                        <img src="{{ Storage::url($img->path) }}" class="w-full h-full object-cover" alt="">
                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="sr-only delete-image-checkbox" id="del-img-{{ $img->id }}">
                        <button type="button"
                            class="delete-img-btn absolute top-1.5 right-1.5 w-6 h-6 bg-white/90 hover:bg-red-500 hover:text-white text-[#5d5f5f] rounded-full flex items-center justify-center shadow-sm transition-all"
                            data-img-id="{{ $img->id }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="delete-overlay hidden absolute inset-0 bg-red-500/50 rounded-xl flex items-center justify-center pointer-events-none">
                            <span class="text-[10px] font-bold text-white bg-red-600 px-2 py-0.5 rounded-full">Supprimer</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Upload zone --}}
                <label for="imageInput"
                    class="flex flex-col items-center justify-center border-2 border-dashed border-[#c4c6d1] rounded-xl p-6 cursor-pointer hover:border-[#002352]/40 hover:bg-[#f2f4f6] transition-all group" id="imageDropZone">
                    <svg class="w-7 h-7 text-[#c4c6d1] group-hover:text-[#27467b] transition-colors mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <p class="text-[12px] font-semibold text-[#5d5f5f] group-hover:text-[#002352] transition-colors">Glisser-déposer</p>
                    <p class="text-[10px] text-[#747780] mt-0.5">PNG, JPG jusqu'à 5MB</p>
                    <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
                </label>

                {{-- New image previews --}}
                <div id="imagePreviews" class="grid grid-cols-3 gap-2 mt-2 empty:hidden"></div>
            </div>

        </div>
    </div>

    {{-- ─── STICKY FOOTER BAR ─── --}}
    <div class="fixed bottom-0 left-0 right-0 lg:left-[260px] z-40">
        <div class="bg-white/90 backdrop-blur-md border-t border-[#edeef0] px-6 py-3 flex items-center justify-between shadow-[0px_-4px_20px_rgba(24,57,110,0.06)]">
            <div class="flex items-center gap-2 text-[12px] text-[#5d5f5f]">
                <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                Modifications non enregistrées
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                   class="px-5 py-2.5 text-[13px] font-semibold text-[#43474f] bg-[#f2f4f6] rounded-xl hover:bg-[#e7e8ea] transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-[13px] font-bold text-white bg-[#002352] rounded-xl hover:bg-[#18396e] transition-colors shadow-[0px_4px_14px_rgba(0,35,82,0.25)]">
                    Enregistrer
                </button>
            </div>
        </div>
    </div>

    {{-- spacer for sticky footer --}}
    <div class="h-16"></div>

</form>
