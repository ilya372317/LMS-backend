<?php

namespace App\Controller\Admin;

use App\Entity\Chat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ChatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Chat::class;
    }

//    /*
//    public function configureFields(string $pageName): iterable
//    {
//        return [
//            IdField::new('id'),
//            TextField::new('title'),
//            TextEditorField::new('description'),
//        ];
//    }
//    */
}
