import Login from './components/Pages/Login';
import Register from './components/Pages/Register';
import BlogList from './components/Pages/BlogList';
import Blog from './components/Pages/Blog';
import Main from './components/Pages/Main';
import Post from './components/Pages/Post';
import Account from './components/Pages/Account/Main';

export default {
    mode: 'history',

    routes: [
        {
            path: '/',
            component: Main,
            children: [
                {
                    path: '/',
                    component: BlogList,
                    name: 'blogs'
                },
                {
                    path: '/blogs/:id',
                    component: Blog,
                    name: 'blog'
                },
                {
                    path: '/blogs/:blog_id/posts/:id',
                    component: Post,
                    name: 'post'
                },
                {
                    path: '/login',
                    component: Login,
                    name: 'login'
                },
                {
                    path: '/register',
                    component: Register,
                    name: 'register'
                },
                {
                    path: '/account',
                    component: Account,
                    name: 'account'
                }
            ]
        },

    ]
}
