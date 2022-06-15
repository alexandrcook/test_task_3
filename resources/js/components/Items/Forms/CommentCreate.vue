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
            <div v-if="formErrors.comment" class="mt-4">
                <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in formErrors.comment">{{ error }}</li>
                </ul>
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
export default {
    name: "CommentCreate",
    props: ['comments', 'post_id'],
    data() {
        return {
            new_comment: {
                post_id: this.$props.post_id,
                message: null
            },
            formErrors: {
                comment: null
            }
        }
    },
    methods: {
        async addComment() {
            this.formErrors.comment = null;
            const { post_id, message } = this.new_comment;
            try {
                const res = await fetch(
                    `/api/posts/${this.$props.post_id}/comments`,
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config,
                        body: JSON.stringify({
                            post_id, message
                        })
                    }
                )
                const data = await res.json();

                if(data.errors){
                    this.formErrors.comment = Object.entries(data.errors).map(error => {
                        const [key, value] = error;
                        return {[key]: value[0]};
                    });
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
