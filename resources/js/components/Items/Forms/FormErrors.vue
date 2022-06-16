<template>
    <div>
        <b>Please correct the following error(s):</b>
        <ul>
            <li v-for="error in $props.errors">
                {{ Object.keys(error)[0] }} - {{Object.values(error)[0]}}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "FormErrors",
    props: ['errors'],
}
</script>
