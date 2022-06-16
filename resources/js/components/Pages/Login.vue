<template>
    <div>
        <form @submit.prevent="login" class="mb-3">
            <div>
                <label for="email">email</label>
                <input class="form-control" name="email" v-model="email" placeholder="email">
            </div>

            <div>
                <label for="password">password</label>
                <input class="form-control" name="password" v-model="password" placeholder="password" type="password">
            </div>

            <br>
            <button class="btn btn-info" type="submit">Login</button>
        </form>

        <div v-if="errors" class="mt-4">
            <FormErrors :errors="errors" />
        </div>
    </div>
</template>

<script>
import FormErrors from "../Items/Forms/FormErrors";
import {getDataErrors} from "../Items/Forms/create-item.service";

export default {
    name: "LoginPage",
    components: {FormErrors},
    data() {
        return {
            email: null,
            password: null,
            errors: null
        };
    },
    methods: {
        async login() {
            this.errors = {};
            const { email, password } = this;
            try {
                const res = await fetch(
                    "/api/login",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            email,
                            password
                        })
                    }
                )
                const data = await res.json();

                if(data.errors){
                    this.errors = getDataErrors(data);
                }

                if(data.data){
                    this.$root.user.api_token = data.data.api_token;
                    this.$root.user.is_admin = data.data.is_admin;
                    this.$root.user.id = data.data.user_id;
                    this.$router.push({name: 'blogs'});
                }

            } catch (err) {
                console.log('err', err)
            }
        }
    }
}
</script>
