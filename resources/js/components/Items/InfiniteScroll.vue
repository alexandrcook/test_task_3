<template>
    <div>
        <slot />
        <div v-if="isLoading && $props.allItemsLoaded">Loading...</div>
        <div v-else></div>
    </div>
</template>

<script>
export default {
    name: "InfiniteScrollTrigger",
    data() {
        return {
            isLoading: false
        }
    },
    props: {
        loadItemsFn: {type: Function},
        allItemsLoaded: {type: Boolean}
    },
    methods: {
        async getItems() {
            this.isLoading = true;
            try {
                await this.loadItemsFn();
            } catch (err) {
                console.log('err', err);
            } finally {
                this.isLoading = false;
            }
        },
        async handleScroll(){
            if(this.$root.isElementScrolledToBottomLine(this.$el.lastChild) && !this.isLoading){
                this.getItems();
            }
        }
    },
    mounted() {
        this.getItems();
    },
    created () {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed () {
        window.removeEventListener('scroll', this.handleScroll);
    },
}
</script>
