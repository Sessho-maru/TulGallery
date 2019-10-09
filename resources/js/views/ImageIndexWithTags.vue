<template>
    <div class="overflow-x-hidden">

        <div v-for="each in post" class="mb-4 px-2 w-1/2 md:w-1/3 lg:w-1/6">
            <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, t: t, mode: 'tag' } }">
                <img class="block h-auto w-full hover:opacity-75" v-bind:src="each.data.url" alt="placeholder">
            </router-link>
        </div>

    </div>
</template>

<script>
export default {

    name: 'ImageIndexWithTags',

    props: [
        'api_token',
    ],

    data() {
        return {
            post: "",
            t: []
        }
    },

    mounted() {
        this.t = this.$route.params.t;

        axios.get('/imgs/tags/', {
            params: {
                data: this.$route.params.t
            }
        })
        .then( response => {
            this.post = response.data.data;
        })
        .catch( errors => {
            alert("Something goes Wrong");
        });
    },

}
</script>

<style scoped>

</style>