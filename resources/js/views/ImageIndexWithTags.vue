<template>
    <div class="overflow-x-hidden">

        <div v-for="each in currentPage" class="mb-4 px-2 w-1/2 md:w-1/3 lg:w-1/6">
            <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, t: t, mode: 'tag', currentPageIndex: index } }">
                <img class="block h-auto w-full hover:opacity-75" v-bind:src="each.data.url" alt="placeholder">
            </router-link>
        </div>

        <div class="flex flex-1 justify-between">
            <button @click="changeCurrentPage(index - 1); index--;">Prev</button>
            <button @click="changeCurrentPage(index + 1); index++;">Next</button>
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
                let posts = response.data.data;
                let pageSize = Math.ceil(posts.length / 24);

                for (let i = 0; i < pageSize; i++)
                {
                    this.paginated[i] = new Array();
                }

                let total = posts.length - 1;
                let i = 0;
                let j = 0;

                for (total; total >= 0; total--)
                {
                    this.paginated[i][j] = posts[total];

                    if (j % 23 == 0 && j != 0)
                    {
                        i++;
                        j = 0;
                    }
                    else
                    {
                        j++;
                    }
                }

                if (this.$route.params.currentPageIndex != null)
                {
                    this.currentPage = this.paginated[this.$route.params.currentPageIndex];
                    this.index = this.$route.params.currentPageIndex;
                    console.log("current Page Index = " + this.index);
                }
                else
                {
                    this.changeCurrentPage(this.index);
                }
                
            })
            .catch( errors => {
                alert("Unable to Fetch Images");
            });
    },

    methods: {
        changeCurrentPage(index)
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