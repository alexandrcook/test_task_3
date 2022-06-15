<template>
    <div class="d-flex justify-content-between mb-2">
        <div>
            Commentary (id: {{comment.id}}) - {{comment.message.slice(0,20)}}...
            <div class="mr-2">(deleted at {{comment.deleted_at}})</div>
        </div>
        <div class="">
            <button @click="$props.restore('comment', comment.id)" class="btn btn-success mr-2">Restore</button>
            <button @click="$props.forceRemove('comment', comment.id)" class="btn btn-danger">Force DELETE!!!</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Comment",
    props: ['comment', 'restore', 'forceRemove']
}
</script>

<style scoped>

</style>
