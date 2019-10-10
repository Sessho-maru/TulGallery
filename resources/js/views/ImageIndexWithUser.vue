<template>
    <div class="overflow-x-hidden">

        <div v-for="each in post" class="mb-4 px-2 w-1/2 md:w-1/3 lg:w-1/6">
            <router-link :to="{ path: '/imgs/' + each.data.image_id }">
                <img class="block h-auto w-full hover:opacity-75" v-bind:src="each.data.url" alt="placeholder">
            </router-link>
        </div>

    </div>
</template>

<script>
export default {

    name: "ImageIndexWithUser",

    props: [
        'api_token'
    ],

    data() {
        return {
            post: ""
        }
    },

    mounted() {
        axios.get('/api/imgs/index/' + this.$route.params.id)
            .then( response => {
                this.post = response.data.data;
            })
            .catch( errors => {
                alert("Unable to Fetch Images");
            });
    }
}
</script>

<style scoped>

</style>