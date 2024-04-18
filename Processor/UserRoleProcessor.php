<?php
require_once '../Repository/RoleRepository.php';
require_once '../Repository/UserRepository.php';
require_once '../Model/Response.php';
require_once '../Model/User.php';
require_once '../Model/Role.php';
require_once '../Model/UserRole.php';

class UserRoleProcessor
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
    }

    /**
     * @return UserRole[]
     */
    public function assignRoles(): array
    {
        $roles = $this->roleRepository->findAll();
        $allPlayers = array_merge(
            [$this->userRepository->findOneById($_SESSION['user_id'])],
            $this->userRepository->findPcPlayers()
        );
        return $this->assignRoleToPlayers($roles, $allPlayers);
    }

    /**
     * @param Role[] $roles
     */
    private function getVillagerRole(array $roles): ?Role
    {
        foreach ($roles as $role) {
            if ($role->isVillager()) {
                return $role;
            }
        }
        return null;
    }

    /**
     * @param Role[] $roles
     * @param User[] $users
     * @return UserRole[]
     */
    private function assignRoleToPlayers(array $roles, array $users): array
    {
        $villagerRole = $this->getVillagerRole($roles);
        $userRoles = [];
        foreach ($users as $user) {
            //if all roles are taken users will be villager
            if (empty($roles)) {
                $userRoles[] = UserRole::fromValues($user, $villagerRole);
                continue;
            }
            $roleIndex = array_rand($roles);
            $playerRole = $roles[$roleIndex];
            $userRoles[] = UserRole::fromValues($user, $playerRole);
            // Remove the selected role from the array
            unset($roles[$roleIndex]);
        }
        return $userRoles;
    }

    /**
     * @param UserRole[] $userRoles
     */
    public function getVillagerToEliminate(array $userRoles){
        $villagers = [];
        foreach ($userRoles as $userRole){
            if($userRole->getRole()->isVillager()){
                $villagers[] = $userRole->getUser();
            }
        }

        $userIndex = array_rand($villagers);
        return $villagers[$userIndex];
    }

    /**
     * @param GameUserRole[] $gameUserRoles
     * @return User
     */
    public function getRandomNonMafiaUser(array $gameUserRoles): User{
        $users = [];
        foreach ($gameUserRoles as $gameUserRole){
            if(!$gameUserRole->getUserRole()->getRole()->isMafia()){
                $users[] = $gameUserRole->getUserRole()->getUser();
            }
        }

        $userIndex = array_rand($users);
        return $users[$userIndex];
    }
}