<?php

namespace App\Form\Category;

use App\Entity\Category\Category;
use App\Entity\Category\MenuCategory;
use App\Entity\Page\Page;
use App\Enum\MenuCategoryTypeEnum;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    private ?int $categoryId;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->categoryId = $options['attr']['category']->getId();

        $builder
            ->add('title', TextType::class, ['label' => 'Nom de la catégorie'])
            ->add('sort', NumberType::class, [
                'label' => 'Ordre d\'affichage',
                'attr' => [
                    'min' => 1,
                ]
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                [$this, 'onPreSetData']
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $category = $event->getData();

        $addParentField = false;
        $rootName = '- - - ';
        $className = Category::class;

        if ($category instanceof MenuCategory) {
            $addParentField = true;
            $className = MenuCategory::class;
			$form->add('type', EnumType::class, [
				'label' => 'Type',
				'class' => MenuCategoryTypeEnum::class,
				'choice_label' => function (MenuCategoryTypeEnum $enum) {
					return $enum->getLabel();
				}
			]);

            $form->add('page', EntityType::class, [
                'label' => 'Sélectionnez une page',
                'placeholder' => '- - - ',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'class' => Page::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p');
                }
            ]);

            $form->add('url', TextType::class, [
                'label' => 'URL complète de la page',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: https://ab6net.net']
            ]);

            $form->add('newTab', CheckboxType::class, [
                'label' => 'Ouvrir le lien dans un nouvel onglet ?',
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch'
                ],
            ]);

            $form->add('itemId', TextType::class, [
                'label' => 'Id de l’élément de menu',
                'required' => false,
            ]);
            $form->add('itemClasses', TextType::class, [
                'label' => 'Classes de l’élément de menu',
                'required' => false,
            ]);
        }


        if ($addParentField) {
            $form->add('parent', EntityType::class, [
                'label' => 'Catégorie parent',
                'required' => true,
                'class' => $className,
                'choice_label' => function (Category $category) use ($rootName) {
                    if ($category->getLvl() > 0) {
                        $prefix = str_repeat('- ', $category->getLvl());

                        return $prefix . ' ' . $category->getTitle();
                    }
                    return $rootName;
                },
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (EntityRepository $er) use ($category) {

                    $query = $er->createQueryBuilder('node')
                        ->where('node.root = :treeId');

                    if ($category instanceof MenuCategory) {
                        $query->andWhere('node.type = :type')
                            ->setParameter('type', MenuCategoryTypeEnum::ITEM)
                            ->andWhere('node.lvl  <= 2');
                    } else {
                        $query->andWhere('node.lvl  <= 1');
                    }

                    $query
                        ->setParameter('treeId', $this->categoryId)
                        ->orderBy('node.root, node.lft', 'ASC');

                    return $query;
                }
            ]);
        }
    }
}
