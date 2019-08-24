<?php

namespace App\Menu;

use App\Entity\Radius\Group;
use Doctrine\ORM\EntityManagerInterface;
use Pd\MenuBundle\Builder\ItemInterface;
use Pd\MenuBundle\Builder\Menu;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MainMenu extends Menu
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * Override
     */
    public function createMenu(array $options = []): ItemInterface
    {

        // Create Root Item
        $menu = $this
            ->createRoot('settings_menu', true)// Create event is "settings_menu.event"
            ->setListAttr([])
            ->setChildAttr(['data-parent' => 'grase_radmin_homepage', 'class' => 'nav nav-pills nav-sidebar flex-column nav-child-indent']); // Add Parent Menu to Html Tag

        // Create Menu Items

        $menu->addChild(new DefaultItem('nav_config_groups', $menu->isEvent()), 5)
             ->setLabel('grase.menu.groups')
             ->setRoute('grase_groups')
             ->setChildAttr(['class' => 'nav-treeview'])
        ;
        //->setRoles(['ADMIN_SETTINGS_CONTACT'])

        $usersMenu = $menu->addChild(new DefaultItem('nav_config_users', $menu->isEvent()), 10)
            ->setLabel('grase.menu.users')
            ->setLabelAfterHtml(' <i class="right fas fa-angle-left"></i>')
            ->setLink('#')
            ->setListAttr(['class' => 'nav-item has-treeview'])
            ->setChildAttr(['class' => 'nav nav-treeview'])
            ->setExtra('label_icon', 'people')
            //->setRoles(['ADMIN_SETTINGS_EMAIL'])
        ;
        $usersMenu->addChild(new DefaultItem('nav_config_users-all', $usersMenu->isEvent()))
            ->setLabel('grase.menu.users.allusers')
            ->setRoute('grase_users')
            ->setExtra('label_icon', 'people')
            ;
        $this->buildUserGroupsItems($usersMenu);

        $menu->addChild(new DefaultItem('nav_create_user', $menu->isEvent()), 11)
            ->setLabel('grase.menu.users.new')
            ->setRoute('grase_user_new')
            ->setExtra('label_icon', 'person_add')
            ;


        $menu->addChild(new DefaultItem('nav_report_dhcp_leases', $menu->isEvent()), 15)
            ->setLabel('grase.menu.dhcp_leases')
            ->setRoute('grase_dhcp_leases')
            ->setChildAttr(['class' => 'nav-treeview'])
        ;
        //->setRoles(['ADMIN_SETTINGS_CONTACT'])

        $menu->addChild(new DefaultItem('nav_header_settings', $menu->isEvent()), 30)
             ->setLabel('grase.menu.settings.header')
             ->setListAttr(['class' => 'nav-header'])
        ;


        $settingsMenu = $menu->addChild(new DefaultItem('nav_config_settings', $menu->isEvent()), 30)
            ->setLabel('Settings')
            ->setLabelAfterHtml(' <i class="right fas fa-angle-left"></i>')
            //->setRoute('grase_settings')
            ->setLink('#')
            ->setListAttr(['class' => 'nav-item has-treeview'])
            ->setChildAttr(['class' => 'nav nav-treeview'])
            ->setExtra('label_icon', 'settings_application')
        ;
        //->setRoles(['ADMIN_SETTINGS_GENERAL'])


        $settingsMenu->addChild(new DefaultItem('nav_config_advanced_settings', $settingsMenu->isEvent()), 1)
            ->setLabel('Advanced Settings')
            ->setRoute('grase_advanced_settings')
            ->setExtra('label_icon', 'settings_application')
            ->setChildAttr(['class' => 'nav nav-treeview'])
        ;
        //->setRoles(['ADMIN_SETTINGS_GENERAL'])

        $menu->addChild(new DefaultItem('nav_header_admin', $menu->isEvent()), 40)
             ->setLabel('grase.menu.admin.header')
             ->setListAttr(['class' => 'nav-header'])
        ;

        $settingsMenu = $menu->addChild(new DefaultItem('nav_admin_auditlog', $menu->isEvent()), 40)
                             ->setLabel('grase.menu.admin.auditlog')
                             ->setRoute('grase_auditlog')
                             ->setExtra('label_icon', 'security')
        ;

        $menu->addChild(new DefaultItem('nav_logout', $menu->isEvent()), 100)
            ->setLabel('Logout')
            ->setRoute('_grase_logout')
        ;


        return $menu;
    }

    private function buildUserGroupsItems(ItemInterface $usersMenu)
    {
        $groupRepo = $this->entityManager->getRepository(Group::class);
        $groups = $groupRepo->findAll();
        $groups = $groupRepo->findBy([], ['name' => 'ASC']);
        /** @var Group $group */
        foreach ($groups as $group) {
            if (!$group->getUsergroups()->isEmpty()) {
                $usersMenu->addChild(new DefaultItem('nav_config_users_'.$group->getId(), $usersMenu->isEvent()))
                    ->setLabel($group->getName())
                    ->setRoute('grase_users', ['group' => $group->getName()])
                    ->setExtra('label_icon', 'people_outline')
                    ->setExtra('label_translate', false) // Don't try and translate the group names in the menu

                ;
            }
        }

        return $usersMenu;
    }
}
