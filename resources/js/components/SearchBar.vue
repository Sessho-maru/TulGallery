<template>
    <div>

        <div v-if="focus" @click="focus = false; serchResults = [];" class="bg-black opacity-0 absolute right-0 left-0 top-0 bottom-0 z-10"></div> <!-- toggle this.focus and hide search result modal by clicking anywhere on page -->

        <div id="search_bar" class="relative z-10">
            <div class="absolute">
                <svg viewBox="0 0 24 24" class="w-5 h-5 mt-2 ml-2">
                    <path fill-rule="evenodd" d="M20.2 18.1l-1.4 1.3-5.5-5.2 1.4-1.3 5.5 5.2zM7.5 12c-2.7 0-4.9-2.1-4.9-4.6s2.2-4.6 4.9-4.6 4.9 2.1 4.9 4.6S10.2 12 7.5 12zM7.5.8C3.7.8.7 3.7.7 7.3s3.1 6.5 6.8 6.5c3.8 0 6.8-2.9 6.8-6.5S11.3.8 7.5.8z" clip-rule="evenodd"/>
                </svg>
            </div>
            <input type="text" v-bind:placeholder="selectedTagNames" id="searchTerm" v-model="searchTerm" @input="search" @focus="focus = true" class="w-64 bg-gray-200 border border-gray-400 pl-8 pr-3 py-1 rounded-full text-sm focus:outline-none focus:border-blue-500 focus:shadow focus:bg-gray-100">
            <div v-if="focus" class="absolute bg-blue-900 text-white rounded-lg p-4 w-64 opacity-75 right-0 mr-2 shadow z-20">
                <div v-if="serchResults == 0">No serchResults found for '{{ searchTerm }}'</div>
                <div v-bind:key="result.id" v-for="result in serchResults">
                    <p v-bind:id="result.id" class="py-2" @click="AddTagId(result.id); AddTagName(result.tag_name); hide(result.id); serchResults = []; searchTerm = ''; focus = false">{{ result.tag_name }}</p>
                </div>
            </div>

            <router-link :to="{ name: 'Tags'}">
                <div class="inline" v-if="this.$tag_ids.length > 0">
                    <button @click="$emit('search', selectedTagNames)">Search</button>
                </div>
            </router-link>
        </div>

    </div>
</template>

<script>
import _ from 'lodash';

export default {
    name: "Searachbar",

    props: [
        'api_token'
    ],

    data()
    {
        return {
            searchTerm: "",
            serchResults: [],
            selectedTagNames: "",
            focus: false
        }
    },

    methods: {
        search:_.debounce( function(e) {
            if (this.searchTerm.length < 4)
            {
                return;
            }
            else
            {
                axios.post('/api/search', { api_token: this.api_token, searchTerm: this.searchTerm })
                    .then( response => {
                        this.serchResults = response.data;
                    })
                    .catch ( errors => {
                        console.log(errors.resopnse);
                    });
            }
        }, 300),

        AddTagId(id)
        {
            this.$tag_ids.push(id);
        },

        AddTagName(tagName)
        {
            this.selectedTagNames += tagName + ' || ';
        },

        hide(id)
        {
            var element = document.getElementById(id);
            element.classList.add("hidden");
        }
    }
}
</script>

<style scoped>

</style>