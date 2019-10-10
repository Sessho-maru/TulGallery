<template>
    <div class="overflow-x-hidden">

        <div v-for="each in currentPage" class="mb-4 px-2 w-1/2 md:w-1/3 lg:w-1/6">
            <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, t: t, mode: 'tag' } }">
                <img class="block h-auto w-full hover:opacity-75" v-bind:src="each.data.url" alt="placeholder">
            </router-link>
        </div>

        <div class="flex flex-1 justify-between">
            <button @click="change(index - 1); index--;">Prev</button>
            <button @click="change(index + 1); index++;">Next</button>
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
            paginated: [],
            currentPage: "",
            index: 0,
            t: []
        }
    },

    mounted() {
        this.t = this.$route.params.t;

        axios.get('/imgs/tags/', { params: { data: this.$route.params.t } })
                .then( response => {
                    this.post = response.data.data;

                    var page = Math.ceil(this.post.length / 24);

                    for (var i = 0; i < page; i++)
                    {
                        this.paginated[i] = new Array();
                    }

                    // reverse
                    var i = this.post.length - 1;
                    var j = 0;
                    page = 0;

                    for (i; i >= 0; i--)
                    {
                        this.paginated[page][j] = this.post[i];

                        if (j % 23 == 0 && j != 0)
                        {
                            page++;
                            j = 0;
                        }
                        else
                        {
                            j++;
                        }
                    }
                    this.change(this.index);
                })
                .catch( errors => {
                    alert("Something goes Wrong");
                });
    },

    methods: {
        change(index)
        {   

            if (index < 0)
            {
                this.index++;
                return;
            }

            if (index >= this.paginated.length)
            {
                this.index--;
                return;
            }

            console.log("current Page Index = " + index);
            this.currentPage = this.paginated[index];
        }
    }
}
</script>

<style scoped>

</style>