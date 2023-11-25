<?php

namespace Botble\Services\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Services\Http\Requests\ServicesRequest;
use Botble\Services\Models\Services;

class ServicesForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Services)
            ->setValidatorClass(ServicesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('slug', 'text', [
                'label'      => trans('Slug'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('icon', 'mediaImage', [
                'label' => __('Icon'),
                'label_attr' => ['class' => 'control-label'],
            ])

            ->add('summary', 'textarea', [
                'label'      => __('Summary'),
                'label_attr' => ['class' => 'control-label '],
                'attr'       => [

                    'data-counter' => 120,
                ],
            ])


            ->add('content', 'editor', [
                'label' => __('content'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'with-short-code' => false, // if true, it will add a button to select shortcode
                    'without-buttons' => false, // if true, all buttons will be hidden
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])

            ->add('color', 'color', [
                'label' => __('Color'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('image', 'mediaImage', [
                'label' => __('Image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->setBreakFieldPoint('status');
    }
}
