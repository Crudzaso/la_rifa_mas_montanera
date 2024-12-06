import { usePage } from '@inertiajs/vue3';

export function usePermissions() {
    const hasRole = (roles) => {
        const userRoles = usePage().props.auth?.user?.roles;
        if (!userRoles) return false;
        
        
        const roleArray = Array.isArray(roles) ? roles : [roles];
        

        const userRoleNames = userRoles.map(role => role.name);
        return roleArray.some(role => userRoleNames.includes(role));
    };

    const hasPermission = (permissions) => {
        const userRoles = usePage().props.auth?.user?.roles;
        if (!userRoles) return false;
        
        const permissionArray = Array.isArray(permissions) ? permissions : [permissions];
        
        // Verificar en todos los roles si existe el permiso
        return userRoles.some(role => {
            return permissionArray.some(permission => 
                role.permissions.includes(permission)
            );
        });
    };

    return { hasRole, hasPermission };
}
