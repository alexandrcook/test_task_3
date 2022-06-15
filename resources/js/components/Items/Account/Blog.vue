<template>
    <div>
        <div class="d-flex justify-content-between mb-3">
            <div>
                Blog (id: {{blog.id}}) - {{blog.name.slice(0,20)}}...
                <div class="mr-2">(deleted at {{blog.deleted_at}})</div>
            </div>
            <div>
                <button @click="$props.restore('blog', blog.id)" class="btn btn-success mr-2">Restore</button>
                <button @click="$props.forceRemove('blog', blog.id)" class="btn btn-danger">Force DELETE!!!</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Blog",
    props: ['blog', 'restore', 'forceRemove']
}
</script>

<style scoped>

</style>
