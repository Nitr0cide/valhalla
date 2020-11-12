<?php

namespace App\Form;

use App\Entity\Factures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class FacturesType extends AbstractType
{
    private $userLogin;

    public function __construct(Security $security)
    {
        $this->userLogin = $security->getUser()->getUserLogin();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientName', TextType::class, [
                'label' => 'Nom du client',
            ])
            ->add('factureName', TextType::class)
            ->add('emitted', ChoiceType::class, [
                'choices' => [
                    "J'ai émis cette facture" => true,
                    "J'ai reçu cette facture" => false,
                ],
                'label' => "Qui émet cette facture",
            ])
            ->add('date', DateType::class)
            ->add('prix_ht', NumberType::class, [
                'required' => false,
            ])
            ->add('prix_ttc', NumberType::class, [
                'required' => false,
            ])
            ->add('tva', NumberType::class, [
                'label' => 'Taux de TVA applicable',
                'data' => 20
            ])
            ->add('file_path', FileType::class, [
                'data_class' => null,
                'required' => false,
            ])
            ->add('categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factures::class,
        ]);
    }
}
