<template>
    <div class="overflow-x-hidden">

        <div v-for="each in currentPage" class="mb-4 px-2 w-1/2 md:w-1/3 lg:w-1/6">
            <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, mode: 'normal' } }">
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

    name: "ImageIndex",

    props: [
        'api_token'
    ],

    data() {
        return {
            post: "",
            paginated: [],
            currentPage: "",
            index: 0
        }
    },

    mounted() {
        axios.get('/api/imgs')
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

                // verse
                {
                    /*
                    var i = 0;
                    var j = 0;
                    page = 0;

                    for (i; i < this.post.length; i++)
                    {
                        this.paginated[page][j] = this.post[i];

                        if (i % 23 == 0 && i != 0)
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
                    */
                }
                
            })
            .catch( errors => {
                alert("Unable to Fetch Images");
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