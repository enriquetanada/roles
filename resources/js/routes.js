import Login from './components/Login';
import Users from './components/Users';
import Permission from './components/Permissions';
import Role from './components/Roles';
import Dashboard from './components/Dashboard';
import Registration from './components/Registration';
import PageNotFound from './components/PageNotFound';


export default [
    {
        path: '/',
        name: 'login',
        component: Login,
        meta: { userAuth: false },
    },
    {
        path: '/register',
        name: 'registration',
        component: Registration,
        meta: { userAuth: false },
    },
    {
        path: '/users',
        name: 'users',
        component: Users,
        meta: { userAuth: true },
    },
    {
        path: '/permission',
        name: 'permission',
        component: Permission,
        meta: { userAuth: true },
    },
    {
        path: '/role',
        name: 'role',
        component: Role,
        meta: { userAuth: true },
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { userAuth: true },
    },
     {
        path: '/*',
        component: PageNotFound,
        name: 'notFound',
        meta: { userAuth: false },
     }


]