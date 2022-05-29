import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './../js/routes';

var infiniteScroll =  require('vue-infinite-scroll');

Vue.use(infiniteScroll);
Vue.use(VueRouter);

let app = new Vue({
    el: '#app',
    router: new VueRouter(routes),
    data: {
        user: {
            id: null,
            api_token: null,
            is_admin: false
        },
        fetch_headers_config: {}
    },
    methods: {
        logoutUser(){
            console.log('logout');
            this.user = {
                id: null,
                api_token: null,
                is_admin: false
            }

            this.$router.push({name: 'login'});
        },
        updateFetchHeaderInfo(){
            this.fetch_headers_config = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer '+ this.user.api_token
            }
        },
        tryToGetUserInfoFromLS(){
            let LSUser = JSON.parse(localStorage.getItem('user'));
            console.log(333, LSUser);
            console.log(JSON.stringify(this.user));
            console.log(444, (LSUser !== JSON.stringify(this.user)));

            console.log(7777, (!!LSUser && LSUser !== JSON.stringify(this.user)))

            if (!!LSUser && LSUser !== JSON.stringify(this.user)) {
                this.user = LSUser;
                console.log('user was written from LS to ROOT - ' + this.user);
            }else{
                console.log('user data in LS the same or empty');
            }
        },
        tryToWriteUserInfoToLS(){
            if (JSON.parse(localStorage.getItem('user')) !== JSON.stringify(this.user)) {
                localStorage.setItem('user', JSON.stringify(this.user));
                this.updateFetchHeaderInfo();
                console.log('user was written to LS from ROOT - ' + JSON.stringify(this.user));
            }else{
                console.log('LS already contain right user');
            }
        }
    },
    beforeMount() {
        if(!this.user.id || !this.user.api_token){
            console.log('try to get user from LS mount');
            this.tryToGetUserInfoFromLS();
        }
        this.updateFetchHeaderInfo();
    },
    watch: {
        user: {
            handler() {
                this.tryToWriteUserInfoToLS();
            },
            deep: true
        },
    },
});
