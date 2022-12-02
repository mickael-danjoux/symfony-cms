<?php

namespace App\Form\Page;

use App\Entity\Page\Page;
use App\Entity\User\User;
use App\Enum\PageTypeEnum;
use App\Form\Seo\SeoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class PageType extends AbstractType
{

    public function __construct(private readonly Security $security)
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la page',
            ])
            ->add('path', TextType::class, [
                'label' => 'Chemin d’accès',
                'help' => 'Ne pas commencer par "/"',
                'attr' => [
                    'placeholder' => 'ex: nous-decouvrir',
                ]
            ])
            ->add('customPath', CheckboxType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'd-none'
                ],
                'required' => false
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Publier ?',
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
            ->add('startPublishingAt', DateType::class, [
                'label' => 'Date de début de publication',
                'widget' => 'single_text',
                'html5' => true,
                'input' => 'datetime',
            ])
            ->add('endPublishingAt', DateType::class, [
                'label' => 'Date de fin de publication',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'input' => 'datetime',
            ])
            ->add('content', TextType::class, [
                'label' => false
            ])
            ->add('seo', SeoType::class, [
                'label' => false,
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                [$this, 'onPreSetData']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $isSuperAdmin = $this->security->isGranted('ROLE_SUPER_ADMIN');

        if ($isSuperAdmin) {

            $form->add('type', EnumType::class, [
                'label' => 'Type de page',
				'class' => PageTypeEnum::class,
				'choice_label' => function (PageTypeEnum $enum) {
					return $enum->getLabel();
				}
			]);

            $form->add('controller', TextType::class, [
                'label' => 'Fonction du contrôleur',
                'required' => false
            ]);
            $form->add('routeName', TextType::class, [
                'label' => 'Nom interne de la route',
                'required' => false
            ]);
        }
    }
}
