import { usePage } from '@inertiajs/vue3';

export function usePermissions() {
    const hasRole = (roles) => {
        const userRoles = usePage().props.auth?.user?.roles;
        if (!userRoles) return false;
        
        const roleArray = Array.isArray(roles) ? roles : [roles];
        return userRoles.some(role => roleArray.includes(role.name));
    };

    const hasPermission = (permissions) => {
        const userRoles = usePage().props.auth?.user?.roles;
        if (!userRoles) return false;
        
        const permissionArray = Array.isArray(permissions) ? permissions : [permissions];
        const userPermissions = userRoles.flatMap(role => role.permissions || []);
        return userPermissions.some(p => permissionArray.includes(p.name));
    };

    return { hasRole, hasPermission };
}
