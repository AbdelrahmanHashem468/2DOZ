<template>
    <div class="container">
        test
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name:'ExampleComponent',
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
