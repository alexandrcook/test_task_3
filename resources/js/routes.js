import Login from './components/Pages/Login';
import Register from './components/Pages/Register';
import BlogList from './components/Pages/BlogList';
import Blog from './components/Pages/Blog';
import PostList from './components/Pages/PostList';
import Post from './components/Pages/Post';
import Account from './components/Pages/Account/Main';

export default {
    mode: 'history',

    routes: [
        {
            path: '/',
            component: PostList,
            name: 'home',
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
            path: '/blogs',
            component: BlogList,
            name: 'blogs'
        },
        {
            path: '/blogs/:id',
            component: Blog,
            name: 'blog'
        },

        {
            path: '/posts',
            component: PostList,
            name: 'posts'
        },
        {
            path: '/posts/:id',
            component: Post,
            name: 'post'
        },

        {
            path: '/account',
            component: Account,
            name: 'account'
        }
    ]
}
