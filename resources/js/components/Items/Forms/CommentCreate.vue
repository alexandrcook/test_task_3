<template>
    <div>
        <div v-if="$root.user.id">
            <form @submit.prevent="addComment">
                <textarea v-model="new_comment.message" class="form-control"></textarea>
                <div class="d-flex justify-content-between mt-3">
                    <h5>Add new commentary</h5>
                    <button type="submit" class="btn btn-info">Add</button>
                </div>
            </form>

            <div v-if="formErrors" class="mt-4">
                <FormErrors :errors="formErrors" />
            </div>
        </div>
        <div v-else>
            <hr>
            ... only registered / logged user could write commentaries
            <hr>
        </div>
    </div>
</template>

<script>
import {createNewComment, getDataErrors} from "./create-item.service";
import FormErrors from "./FormErrors";

export default {
    name: "CommentCreate",
    components: {
        FormErrors,
    },
    props: ['comments', 'post_id'],
    data() {
        return {
            new_comment: {
                post_id: this.$props.post_id,
                message: null
            },
            formErrors: null
        }
    },
    methods: {
        async addComment() {
            this.formErrors = null;
            const { post_id, message } = this.new_comment;
            try {
                const data = await createNewComment(post_id, message, this.$root.fetch_headers_config);

                if(data.errors){
                    this.formErrors = getDataErrors(data);
                }

                if(data.data){
                    this.comments.items.unshift(data.data);
                    this.new_comment.message = null;
                }

            } catch (err) {
                console.log('err', err)
            }
        },
    }
}
</script>

<style scoped>

</style>
