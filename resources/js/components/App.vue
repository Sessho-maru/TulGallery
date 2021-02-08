<template>
    
    <div v-if="!someone">Loading...</div>
    <div v-else>

        <div class="h-screen bg-white">
            <div class="flex">

                <div class="bg-gray-200 w-48 h-screen border-r-2 border-gray-400">

                    <nav class="pt-4">

                        <a :href="$router.resolve({name: 'Index'}).href">
                            <svg width="191.8324951171875px" height="122.021240234375px" xmlns="http://www.w3.org/2000/svg" viewBox="90.16622314453124 -11.436279296875 319.6675537109375 172.87255859375" style="background:rgb(29,181,147)" preserveAspectRatio="xMidYMid"><defs><linearGradient id="editing-shadow-gradient1" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#000000" stop-opacity="0"></stop><stop offset="0.2" stop-color="#000000" stop-opacity="0"></stop><stop offset="1" stop-color="#000000" stop-opacity="1"></stop></linearGradient><linearGradient id="editing-shadow-gradient2" x1="-0.254709580222772" x2="1.2547095802227721" y1="-0.15605902899050716" y2="1.1560590289905073"><stop offset="0" stop-color="#ffffff"></stop><stop offset="1" stop-color="#fec918"></stop></linearGradient><filter id="editing-shadow" x="-100%" y="-100%" width="300%" height="300%"><feGaussianBlur stdDeviation="2"></feGaussianBlur><feComposite operator="in" in2="SourceGraphic"></feComposite><feGaussianBlur stdDeviation="1"></feGaussianBlur></filter></defs><g transform="translate(250,75)"><g transform="translate(-29.4, -1.2)"><g transform="skewX(45)"><g filter="url(#editing-shadow)"><g transform="translate(-54.18999910354614, 35.38999938964844)"><path d="M28.76 0L22.84 0L22.84-60.16L4.23-60.16L4.70-65.80L47.28-65.80L46.81-60.16L28.76-60.16L28.76 0ZM60.54-6.02L60.54-6.02L60.54-6.02Q62.60-5.26 67.12-5.26L67.12-5.26L67.12-5.26Q71.63-5.26 78.77-8.93L78.77-8.93L78.77-47L84.88-47.47L84.88 0L78.77 0L78.77-5.08L78.77-5.08Q71.82 0.28 64.58 0.28L64.58 0.28L64.58 0.28Q55.55 0.28 51.51-5.08L51.51-5.08L51.51-5.08Q49.44-7.71 49.44-12.41L49.44-12.41L49.44-47L55.55-47.47L55.55-14.01L55.55-14.01Q55.55-8.18 60.54-6.02ZM104.15 0L98.04 0L98.04-70.03L104.15-70.50L104.15 0Z" fill="url(#editing-shadow-gradient1)"></path></g></g></g></g><g><g transform="translate(-54.18999910354614, 35.38999938964844)">
                                <path d="M28.76 0L22.84 0L22.84-60.16L4.23-60.16L4.70-65.80L47.28-65.80L46.81-60.16L28.76-60.16L28.76 0ZM60.54-6.02L60.54-6.02L60.54-6.02Q62.60-5.26 67.12-5.26L67.12-5.26L67.12-5.26Q71.63-5.26 78.77-8.93L78.77-8.93L78.77-47L84.88-47.47L84.88 0L78.77 0L78.77-5.08L78.77-5.08Q71.82 0.28 64.58 0.28L64.58 0.28L64.58 0.28Q55.55 0.28 51.51-5.08L51.51-5.08L51.51-5.08Q49.44-7.71 49.44-12.41L49.44-12.41L49.44-47L55.55-47.47L55.55-14.01L55.55-14.01Q55.55-8.18 60.54-6.02ZM104.15 0L98.04 0L98.04-70.03L104.15-70.50L104.15 0Z" fill="url(#editing-shadow-gradient2)"></path></g></g></g>
                            </svg>
                        </a>

                        <p class="pl-6 pt-12 text-gray-500 uppercase font-bold text-xs">Create</p>
                        <router-link :to="{ name: 'Create', params: { api_token: someone.api_token } }" class="pl-6 flex items-center py-2 hover:text-blue-300 text-sm">
                            <svg viewBox="0 0 24 24" class="fill-current text-blue-600 w-5 h-5">
                                <path d="M23.3 11.9c0 .9-.6 1.4-1.4 1.4h-8.5v8.5c0 .9-.6 1.4-1.4 1.4s-1.4-.6-1.4-1.4v-8.5H2c-.9 0-1.4-.6-1.4-1.4 0-.9.6-1.4 1.4-1.4h8.5V1.9c0-.9.6-1.4 1.4-1.4s1.4.6 1.4 1.4v8.5h8.5c.9 0 1.5.6 1.5 1.5z"/>
                            </svg>

                            <div class="pl-3 tracking-wise">Add new</div>
                        </router-link>


                        <p class="pl-6 pt-12 text-gray-500 uppercase font-bold text-xs">Tag</p>
                        <router-link to="/tags" class="pl-6 flex items-center py-2 hover:text-blue-300 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-current text-blue-600 w-5 h-5">
                                <path class="st0" d="M20 2h-1V1c0-.6-.4-1-1-1s-1 .4-1 1v1h-4V1c0-.6-.4-1-1-1s-1 .4-1 1v1H7V1c0-.6-.4-1-1-1S5 .4 5 1v1H4C1.8 2 0 3.8 0 6v14c0 2.2 1.8 4 4 4h16c2.2 0 4-1.8 4-4V6c0-2.2-1.8-4-4-4zM4 4h1v1c0 .6.4 1 1 1s1-.4 1-1V4h4v1c0 .6.4 1 1 1s1-.4 1-1V4h4v1c0 .6.4 1 1 1s1-.4 1-1V4h1c1.1 0 2 .9 2 2v2H2V6c0-1.1.9-2 2-2zm16 18H4c-1.1 0-2-.9-2-2V10h20v10c0 1.1-.9 2-2 2zM8 14c0 .6-.4 1-1 1H5c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1zm6 0c0 .6-.4 1-1 1h-2c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1zm6 0c0 .6-.4 1-1 1h-2c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1zM8 18c0 .6-.4 1-1 1H5c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1zm6 0c0 .6-.4 1-1 1h-2c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1zm6 0c0 .6-.4 1-1 1h-2c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1z"/>
                            </svg>

                            <div class="pl-3 tracking-wise">Tag list</div>
                        </router-link>     
                    
                    
                        <p class="pl-6 pt-12 text-gray-500 uppercase font-bold text-xs">Settings</p>
                        <router-link to="/logout" class="pl-6 flex items-center py-2 hover:text-blue-300 text-sm">
                            <svg viewBox="0 0 24 24" class="fill-current text-blue-600 w-5 h-5">
                                <path d="M21 3h-3.8c-.7 0-1.3-.6-1.3-1.3S16.5.4 17.2.4h5.1c.7 0 1.3.6 1.3 1.3v20.5c0 .7-.6 1.3-1.3 1.3h-5.1c-.7 0-1.3-.6-1.3-1.3 0-.7.6-1.3 1.3-1.3H21V3zm-6.9 7.7L8.6 5.2c-.5-.5-.6-1.3-.1-1.8s1.3-.5 1.8 0l7.7 7.7c.8.8.2 2.2-.9 2.2H1.8c-.7 0-1.3-.6-1.3-1.3 0-.7.6-1.3 1.3-1.3h12.3zm-1.6 4.8c.5-.5 1.3-.4 1.8.1s.4 1.3-.1 1.8l-3.8 3.2c-.5.5-1.3.4-1.8-.1-.6-.5-.5-1.3 0-1.7l3.9-3.3z"/>
                            </svg>

                            <div class="pl-3 tracking-wise">Logout</div>
                        </router-link>

                    </nav>

                </div> <!-- left section -->


                <div class="flex flex-col flex-1 h-screen">

                    <div class="h-16 px-6 border-b border-gray-400 flex items-center justify-between">
                        <div class="mr-32">
                            <div v-html="selectedTagNames"></div>
                        </div>
                        
                        <div v-if="$route.name === 'Index' || $route.name === 'IndexPostedBy' || $route.name === 'IndexTaggedBy'">
                            <SearchBar id="search_bar" @NewTagInserted="displayTags"/>
                        </div>
                    </div> <!-- top section -->

                    <router-view v-bind:api_token="someone.api_token" v-bind:user_id="someone.id" @flushSeletedTagNames="selectedTagNames = ''" class="flex flex-1 flex-wrap p-6" /><!-- bottom section -->
                    
                </div> <!-- right section -->

            </div>
        </div>

    </div>
    
</template>

<script>
import SearchBar from '../components/SearchBar';

export default {
    name: "App",

    data()
    {
        return {
            someone: {},
            selectedTagNames: ""
        }
    },

    props: [
        'user'
    ],

    components: {
        SearchBar
    },

    created()
    {

    },

    mounted() {
        if (this.user === undefined)
        {
            console.log("Guest");
        }
        else
        {
            this.someone = this.user;
            console.log("User login as", this.someone);
        }
    },

    destroyed()
    {

    },

    methods: {
        displayTags()
        {
            this.selectedTagNames = "";
            let size = this.$globalParams.tagObjectArray.length;

            this.$globalParams.tagObjectArray.map( (each, i) => {
                if (i < (size - 1))
                {
                    this.selectedTagNames += `
                        <p class="inline uppercase text-blue-500 text-3xl">${each.name}</p><p class="inline mx-3">OR</p>
                    `;
                }
                else
                {
                    this.selectedTagNames += `
                        <p class="inline uppercase text-blue-500 text-3xl">${each.name}</p>
                    `;
                }
                
            });
        }
    }
}
</script>

<style scoped>
    #display {
        white-space: nowrap;
    }
</style>