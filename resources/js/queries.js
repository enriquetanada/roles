import Vue from 'vue';
import axios from 'axios';
import store from './store';

let queries = {
    saveLogin: `mutation saveLogin($user: LoginInput) {
        saveLogin(user: $user) {
            error, 
            message,
            token,
            permissions {
                id,
                name
            }
        }
    }`,
    saveRegistration: `mutation saveRegistration($user: LoginInput) {
        saveRegistration(user: $user) {
            error, 
            message,
        }
    }`,
    getUser: `query getUser {
        getUser {
            email,
        }
    }`,
    getUsersData: `query getUsersData {
        getUsersData {
            users {
                id,
                email,
                name
                roles {
                    id,
                    name
                }
            },
            roles {
                id,
                name
            },
            error,
            message
        }
    }`,
    saveAddUser: `mutation saveAddUser($user: UserInput) {
        saveAddUser(user: $user) {
            error,
            message
        }
    }`,
    saveEditUser: `mutation saveEditUser($user: UserInput) {
        saveEditUser(user: $user) {
            error,
            message
        }
    }`,
    deleteUser: `query deleteUser($deleteUser: Int) {
        deleteUser(deleteUser: $deleteUser){
            error,
            message
        }
    }`,
    getPermissions: `query getPermissions {
        getPermissions {
            id,
            name,
            error,
            message
        }
    }`,
    saveAddPermission: `mutation saveAddPermission($permission: PermissionInput) {
        saveAddPermission(permission: $permission) {
            error,
            message
        }
    }`,
    saveEditPermission: `mutation saveEditPermission($permission: PermissionInput) {
        saveEditPermission(permission: $permission) {
            error,
            message
        }
    }`,
    deletePermission:  `query deletePermission($deletePermission: Int) {
        deletePermission(deletePermission: $deletePermission){
            error,
            message
        }
    }`,
    getRolesData: `query getRolesData {
        getRolesData {
            roles {
                id,
                name
                permissions {
                    id,
                    name
                }
            }
            permissions {
                id,
                name
            },
            error,
            message
        },
    }`,
    saveAddRole: `mutation saveAddRole($role: RoleInput) {
        saveAddRole(role: $role) {
            error,
            message
        }
    }`,
    saveEditRole:  `mutation saveEditRole($role: RoleInput) {
        saveEditRole(role: $role) {
            error,
            message
        }
    }`,
    deleteRole: `query deleteRole($deleteRole: Int) {
        deleteRole(deleteRole: $deleteRole){
            error,
            message
        }
    }`,
}

let userQuery = [
    'getUser', 
    'getUsersData', 
    'saveAddUser', 
    'saveEditUser', 
    'deleteUser', 
    'getPermissions', 
    'saveAddPermission', 
    'saveEditPermission',
    'deletePermission',
    'getRolesData',
    'saveAddRole',
    'saveEditRole',
    'deleteRole'
];

function getApiUrl(queryName) {
    let segment = '';

    if (userQuery.some((q) => q == queryName)) {
        segment = '/user';
    }

    return `/graphql${segment}`;
}

Vue.prototype.$query = function (queryName, queryVariables) {
    let token;

    if (userQuery.some((q) => q == queryName)) {
        token = store.state.userApiToken;
    }

    let options = {
        url: getApiUrl(queryName),
        method: 'POST',
        data: {
            query: queries[queryName],
        },
    };

    if (queryVariables) options.data.variables = queryVariables;

    if (token) {
        options.headers = {
            Authorization: `Bearer ${token}`,
        };
    }

    return axios(options);
};