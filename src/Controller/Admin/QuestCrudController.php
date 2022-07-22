<?php

namespace App\Controller\Admin;

use App\Entity\Quest;
use App\Enum\Quest\QuestStatus;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Quest::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield TextareaField::new('description');
        yield ChoiceField::new('status')
            ->setChoices(QuestStatus::toPreviewArray())
            ->setRequired(true);
    }

}
