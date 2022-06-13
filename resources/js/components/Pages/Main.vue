<template>
    <div>
        <Navigation></Navigation>
        <hr>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>

import Navigation from "../Items/Navigation";

export default {
    name: "Wrapper",
    components: {
        Navigation
    },
    data() {
        return {
            posts: {
                items: [],
                loaded: false
            },

        };
    },
    methods: {

    },
    mounted() {

    }
}
</script>
