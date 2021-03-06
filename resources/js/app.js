import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './../js/routes';

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

            if (!!LSUser && LSUser !== JSON.stringify(this.user)) {
                this.user = LSUser;
            }
        },
        tryToWriteUserInfoToLS(){
            if (JSON.parse(localStorage.getItem('user')) !== JSON.stringify(this.user)) {
                localStorage.setItem('user', JSON.stringify(this.user));
                this.updateFetchHeaderInfo();
            }
        },
        isElementScrolledToBottomLine(el, distanceToBottomLine = 200){
            let rect = el.getBoundingClientRect();
            let elemTop = rect.top;
            let elemBottom = rect.bottom;
            return elemTop < (window.innerHeight + distanceToBottomLine) && elemBottom >= 0;
        }
    },
    beforeMount() {
        if(!this.user.id || !this.user.api_token){
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
