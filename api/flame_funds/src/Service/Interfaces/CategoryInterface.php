<?php

namespace App\Service\Interfaces;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

interface CategoryInterface
{
    public function addCategory(User $user, String $name, String $details);
    public function removeCategory(int $categoryId);
    public function editCategory(int $categoryId, String $newName, String $newDetails);
    public function getCategory(int $categoryId);
    public function getCategories(User $user);
    public function getCategoryType();
}