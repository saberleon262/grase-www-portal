<?php

namespace App\Form\Radius;

use App\Repository\SettingRepository;
use App\Util\SettingsUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DataLimitType
 * Subform form selecting data limits
 */
class DataLimitType extends AbstractType
{
    private $settingRepository;
    private $settingsUtils;

    /**
     * DataLimitType constructor.
     *
     * @param SettingRepository $settingRepository
     * @param SettingsUtils     $settingsUtils
     */
    public function __construct(SettingRepository $settingRepository, SettingsUtils $settingsUtils)
    {
        $this->settingRepository = $settingRepository;
        $this->settingsUtils = $settingsUtils;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$this->settingRepository->find()
        if ($options['create']) {
            $builder->add(
                'dataLimitDropdown',
                ChoiceType::class,
                [
                    'placeholder' => '',
                    'label'       => 'grase.form.user.datalimit.dropdown.label',
                    'required'    => false,
                    'choices'     => array_merge(
                        ['Inherit from Group' => 'inherit'],
                        $this->settingsUtils->mbOptionsArray()
                    ),
                ]
            );
        }
        $builder->add('dataLimitCustom', NumberType::class, [
            'label'    => 'grase.form.user.datalimit.custom.label',
            'required' => false,
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'create' => false,
            ]
        );

        $resolver->setRequired('create');
        $resolver->setAllowedTypes('create', 'boolean');
    }
}
