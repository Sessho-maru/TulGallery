<template>

    <div class="overflow-x-hidden">
        <div class="flex flex-1 flex-col">

            <div v-bind:key="char" v-for="char in letter">
                <p class="pt-6 text-gray-600 font-bold uppercase text-sm">{{ char }}</p>
                <div v-bind:key="tag.id" v-for="tag in tags">
                    <div v-if="char === tag.tag_name[0]">
                        <div class="flex flex-row px-2 py-2">
                            <span class="inline-block bg-gray-300 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-4">{{ tag.tag_name }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
export default {
    name: 'AllTags',

    data() {
        return {
            tags: [],
            letter: [],
            index: 0
        }
    },

    mounted()
    {
        axios.get('/api/tags')
            .then( response => {
                this.tags = response.data;
                this.tags.map( (each) => {
                    console.log("letter.peek(): ", this.letter[this.letter.length - 1]);
                    console.log("each.tag_name.first element: ", each.tag_name[0]);
                    
                    if (this.letter[this.letter.length - 1] !== each.tag_name[0])
                    {
                        this.letter.push(each.tag_name[0]);
                        console.log("this.letter was inserted, ", this.letter);
                    }
                    console.log("------------------------------");
                });                
            })
            .catch( errors => {
                alert("unable to patch");
            });
    }
}
</script>

<style scoped>

</style>