@php
    $oldOption = old('options', []) ?? [];
    $currentProductOption = $product['options'] ?? [];
    if (!empty($oldOption)) {
        $currentProductOption = $oldOption;
    }
@endphp

@push('header')
    <script>
        window.productOptions = {
            productOptionLang: {!! Js::from(trans('plugins/ecommerce::product-option')) !!},
            coreBaseLang: {!! Js::from(trans('core/base::forms')) !!},
            currentProductOption: {!! Js::from($currentProductOption) !!},
            options: {!! Js::from($options) !!}
        }
    </script>
@endpush
<div class="product-option-form-wrap">
    <div class="product-option-form-group">
        <div class="product-option-form-body mt-3 mb-3">
            <input type="hidden" name="has_product_options" value="1">
            <div class="accordion" id="accordion-product-option"></div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <button type="button" class="btn btn-info add-new-option"
                        id="add-new-option">{{ trans('plugins/ecommerce::product-option.add_new_option') }}</button>
            </div>
            @if (count($globalOptions))
                <div class="col-12 col-md-6 d-flex justify-content-end">
                    <div class="ui-select-wrapper d-inline-block" style="width: 200px;">
                        <select id="global-option" class="form-control ui-select">
                            <option
                                value="-1">{{ trans('plugins/ecommerce::product-option.select_global_option') }}</option>
                            @foreach($globalOptions as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <svg class="svg-next-icon svg-next-icon-size-16">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                        </svg>
                    </div>
                    <button type="button" role="button" class="btn btn-info add-from-global-option ms-3">{{ trans('plugins/ecommerce::product-option.add_global_option') }}</button>
                </div>
            @endif
        </div>
    </div>
</div>
<script id="template-option-values-of-field" type="text/x-custom-template">
    <table class="table table-bordered setting-option mt-3">
        <thead>
        <tr>
            <th scope="col">__priceLabel__</th>
            <th scope="col" colspan="2">__priceTypeLabel__</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input type="number" name="options[__index__][values][0][affect_price]" class="form-control option-label" value="__affectPrice__" placeholder="__affectPriceLabel__"/>
                </td>
                <td>
                    <select class="form-control" name="options[__index__][values][0][affect_type]">
                        <option value="0" __selectedFixed__> __fixedLang__</option>
                        <option value="1" __selectedPercent__> __percentLang__</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
</script>
<script id="template-option-type-array" type="text/x-custom-template">
    <table class="table table-bordered setting-option mt-3">
        <thead>
        <tr class="option-row">
            <th scope="col">#</th>
            <th scope="col">__label__</th>
            <th scope="col">__priceLabel__</th>
            <th scope="col" colspan="2">__priceTypeLabel__</th>
        </tr>
        </thead>
        <tbody>
            __optionValue__
        </tbody>
    </table>
</script>

<script id="template-option-type-value" type="text/x-custom-template">
    <tr data-index="__key__">
        <td>
            <i class="fa fa-sort"></i>
            <input type="hidden" class="option-value-order" value="__order__" name="options[__index__][values][__key__][order]">
        </td>
        <td>
            <input type="text" name="options[__index__][values][__key__][option_value]" class="form-control option-label" value="__option_value_input__" placeholder="__labelPlaceholder__" />
        </td>
        <td>
            <input type="number" name="options[__index__][values][__key__][affect_price]" class="form-control affect_price" value="__affectPrice__" placeholder="__affectPriceLabel__" />
        </td>
        <td>
            <select class="form-control affect_type" name="options[__index__][values][__key__][affect_type]">
                <option value="0" __selectedFixed__> __fixedLang__ </option>
                <option value="1" __selectedPercent__> __percentLang__ </option>
            </select>
        </td>
        <td style="width: 50px;">
            <button class="btn btn-default remove-row"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
</script>

<script id="template-option" type="text/x-custom-template">
<div class="accordion-item" data-index="__index__" data-product-option-index="__index__">
    <input type="hidden" name="options[__index__][id]" value="__id__" />
    <input type="hidden" class="option-order" name="options[__index__][order]" value="__order__" />
    <h2 class="accordion-header" id="product-option-__index__">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-product-option-__index__" aria-expanded="true" aria-controls="product-option-__index__">
            __optionName__
        </button>
    </h2>
    <div id="collapse-product-option-__index__" class="accordion-collapse collapse-product-option show" aria-labelledby="product-option-__id__" data-bs-parent="#accordion-product-option">
        <div class="accordion-body">
            <div class="row">
                <div class="col">
                    <label class="form-label" for="">__nameLabel__</label>
                    <input type="text" name="options[__index__][name]" class="form-control option-name" value="__option_name__" placeholder="__namePlaceHolder__">
                </div>
                <div class="col">
                    <label class="form-label" for="">__optionTypeLabel__</label>
                    <select name="options[__index__][option_type]" id="" class="form-control option-type">
                        __optionTypeOption__
                    </select>
                </div>
                <div class="col" style="margin-top: 38px;">
                    <label for="" class="form-label">&nbsp;</label>
                    <input class="option-required" name="options[__index__][required]" id="required-__index__" __checked__ type="checkbox">
                    <label for="required-__index__">__requiredLabel__</label>
                </div>
                <div class="col pt-4">
                    <button type="button" data-index="__index__" role="button" class="remove-option float-end btn btn-default">
                    <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="option-value-wrapper option-value-sortable">
                __optionValueSortable__
            </div>
        </div>
    </div>
</div>
</script>
