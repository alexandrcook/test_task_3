<template>
    <div class="">
        <form @submit.prevent="registration" class="mb-3">
            <div>
                <label for="name">name</label>
                <input class="form-control" name="name" v-model="name" placeholder="name">
            </div>

            <div>
                <label for="surname">surname</label>
                <input class="form-control" name="surname" v-model="surname" placeholder="surname">
            </div>

            <div>
                <label for="nickname">nickname</label>
                <input class="form-control" name="nickname" v-model="nickname" placeholder="nickname">
            </div>

            <div>
                <label for="phone">phone</label>
                <input class="form-control" name="phone" v-model="phone" placeholder="phone">
            </div>

            <div>
                <label for="address">address</label>
                <input class="form-control" name="address" v-model="address" placeholder="address">
            </div>

            <div>
                <label for="city">city</label>
                <input class="form-control" name="city" v-model="city" placeholder="city">
            </div>

            <div>
                <label for="state">state</label>
                <input class="form-control" name="state" v-model="state" placeholder="state">
            </div>

            <div>
                <label for="zipcode">zipcode</label>
                <input class="form-control" name="zipcode" v-model="zipcode" placeholder="zipcode">
            </div>

            <div>
                <label for="email">email</label>
                <input class="form-control" name="email" v-model="email" placeholder="email">
            </div>

            <div>
                <label for="password">password</label>
                <input class="form-control" name="password" v-model="password" placeholder="password" type="password">
            </div>

            <br>
            <button class="btn btn-info" type="submit">Register</button>
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
    name: "RegisterPage",
    components: {FormErrors},
    data() {
        return {
            name: null,
            surname: null,
            nickname: null,
            phone: null,
            address: null,
            city: null,
            state: null,
            zipcode: null,
            email: null,
            password: null,
            errors: null
        };
    },
    methods: {
        async registration() {
            this.errors = {};
            const { name, surname, nickname, phone, address, city, state, zipcode, email, password } = this;
            try {
                const res = await fetch(
                    "/api/register",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            name,
                            surname,
                            nickname,
                            phone,
                            address,
                            city,
                            state,
                            zipcode,
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
