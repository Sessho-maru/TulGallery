<template>
    <div class="overflow-x-hidden">
        <div class="flex flex-1 flex-col">

            <div v-if="loading">Loading....</div>
            <div v-else>
                <form @submit.prevent="createNewPost">
                    <div class="pb-2">
                        <label for="image" class="text-blue-500 pt-2 uppercase text-xs font-bold">Image</label>
                        <input id="image" type="file" ref="file" class="pt-5 w-full border-b pb-2 focus:outline-none focus:border-blue-400" enctype="multipart/form-data" @change="fileHandle" @click="clearError('error_image')"/>
                    </div>
                    <p id="error_image" class="text-red-600 text-sm font-bold"></p>

                    <div class="py-8">
                        <label for="description" class="text-blue-500 pt-2 uppercase text-xs font-bold">Description</label>
                        <div class="pt-5">
                            <ckeditor id="description" type="classic" v-model="objPost.description"></ckeditor>
                        </div>
                    </div>

                    <div class="pb-2">
                        <label for="tag" class="text-blue-500 pt-2 uppercase text-xs font-bold">Tags</label>
                        <input id="tag" type="text" v-model="objPost.tags" placeholder="All the tag inputs must be devied by ','" class="pt-5 w-full border-b pb-2 focus:outline-none focus:border-blue-400" @input="clearError('error_tag')">
                    </div>
                    <p id="error_tag" class="text-red-600 text-sm font-bold"></p>
                
                    <div class="flex justify-end pt-8">
                        <a href="#" @click="$router.back()" class="py-2 px-4 text-red-700 border rounded mr-5 hover:border-red-700">Cancel</a>
                        <button id="submitButton" class="bg-blue-500 py-2 px-4 text-white rounded hover:bg-blue-400">Add New Image</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "ImageCreate",

    props: [
        'api_token',
    ],

    data()
    {
        return {
            file: "",

            objPost: {
                url: "",
                description: "",
                tags: "",
                format: {}
            },

            loading: true,
            fileLoaded: false,
            img: ""
        }
    },

    mounted() {
        if (this.api_token === undefined)
        {
            window.location.href = "/login";
        }
        else
        {
            this.loading = false;
        }
    },

    methods: {
        fileHandle()
        {
            if (this.$refs.file.files[0].size > this.$maxSizePerEachItem * 1024 * 1024)
            {
                this.displayError('image', `Too large file size (Maximum: ${this.$maxSizePerEachItem}MB)`);
                document.getElementById('image').value = '';
                return;
            }

            this.file = this.$refs.file.files[0];
            console.log("original image: ", this.file);

            if (this.file.type === 'image/jpeg' || this.file.type === 'image/png' || this.file.type === 'image/gif')
            {
                this.objPost.format.type = "photo";
                this.objPost.format.extension = this.file.type;
            }
            else if (this.file.type === 'video/webm' || this.file.type === 'video/mp4')
            {
                this.objPost.format.type = "webm";
                this.objPost.format.extension = this.file.type;
            }
            else
            {
                alert("Not supported format", this.file.type);
                this.file = null;
                return;
            }

            this.fileLoaded = true;
        },

        validateTags(tags)
        {
            return new Promise((resolve, reject) => {
                axios.post('/api/imgs/validate', {'tags': tags, 'api_token': this.api_token})
                    .then((res) => {
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },

        uploadImageToBucket(formData)
        {
            return new Promise((resolve, reject) => {
                axios.post('/imgs/upload', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((res) => {
                        resolve(res.data.url);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },

        insertRecordThenRegisterTags()
        {
            return new Promise((resolve, reject) => {
                axios.post('/api/imgs', {...this.objPost, 'api_token': this.api_token})
                    .then((res) => {
                        resolve(res.data);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },

        async createNewPost()
        {
            if (this.fileLoaded === false)
            {
                this.displayError('image', "Requires 1 Image at least");
                return;
            }

            if (this.objPost.tags === "")
            {
                this.displayError('tag', "Requires 1 Tag at least");
                return;
            }

            let tags = this.objPost.tags.split(",");
            if (this.hasDuplicates(tags))
            {
                this.displayError('tag', "Inserted Tags aren't unique");
                return;
            }

            document.getElementById("submitButton").disabled = true;
            let noErr = true;

            await this.validateTags(this.objPost.tags) // execute tags validation
                .then((res) => {
                    console.log(res.data.msg);
                })
                .catch((err) => {
                    this.displayError('tag', err.response.data.msg, true);
                    noErr = false;
                });
            if(!noErr) return;

            let formData = new FormData();
            formData.append('file', this.file);

            await this.uploadImageToBucket(formData) // upload Image to S3 Bucket then get Url
                .then((res) => {
                    console.log("New Image was uploaded to S3 Bucket", res);
                    this.objPost.url = res;
                })
                .catch((err) => {
                    // TODO: return proper error message when uploading fails from /imgs/upload
                    noErr = false;
                });
            if(!noErr) return;

            await this.insertRecordThenRegisterTags() // insert new record to Image Model and register tags then redirect
                .then((res) => {
                    console.log(`New Image Model record(id: ${res.data.image_id}) was inserted and tags was registered: "${this.objPost.tags}"`);
                    this.$router.push(res.links.self);
                })
                .catch((err) => {
                    console.log('Unexpected error occured');
                });
        },

        hasDuplicates(array) {
            return (new Set(array)).size !== array.length;
        },

        displayError(target, msg, turnOnBtn = false)
        {
            document.getElementById(`error_${target}`).innerHTML = msg;
            if(turnOnBtn)
            {
                document.getElementById("submitButton").disabled = false;
            }
        },

        clearError(target)
        {
            document.getElementById(target).innerHTML = "";
        },
    }
}
</script>

<style lang="">

</style>