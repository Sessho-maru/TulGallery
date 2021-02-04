<template>
    <div class="overflow-x-hidden">
        <div class="flex flex-1 flex-col">

            <div v-if="loading">Loading....</div>
            <div v-else>
                <form @submit.prevent="submitForm">
                    <div class="pb-2">
                        <label for="image" class="text-blue-500 pt-2 uppercase text-xs font-bold">Image</label>
                        <input id="image" type="file" ref="file" class="pt-5 w-full border-b pb-2 focus:outline-none focus:border-blue-400" enctype="multipart/form-data" @change="fileHandle" @click="clearError('error_image')"/>
                    </div>
                    <p id="error_image" class="text-red-600 text-sm font-bold"></p>

                    <div class="py-8">
                        <label for="description" class="text-blue-500 pt-2 uppercase text-xs font-bold">Description</label>
                        <div class="pt-5">
                            <ckeditor id="description" type="classic" v-model="form.description"></ckeditor>
                        </div>
                    </div>

                    <div class="pb-2">
                        <label for="tag" class="text-blue-500 pt-2 uppercase text-xs font-bold">Tags</label>
                        <input id="tag" type="text" v-model="form.tags" placeholder="All the tag inputs must be devied by ','" class="pt-5 w-full border-b pb-2 focus:outline-none focus:border-blue-400" @input="clearError('error_tag')">
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

            form: {
                image_id: 0,
                url: "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png",
                thumbnail_url: "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png",
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

        hasDuplicates(array) {
            return (new Set(array)).size !== array.length;
        },

        clearError(target)
        {
            document.getElementById(target).innerHTML = "";
        },

        drawThumbnail(imgUrl, scale)
        {
            this.img = new Image();
            this.img.src = imgUrl;
            this.img.crossOrigin = "anonymous";

            return new Promise( (resolve, reject) => {
                this.img.onload = () => {
                    const elem = document.createElement('canvas');
                    elem.width = Math.floor(this.img.width * scale);
                    elem.height = Math.floor(this.img.height * scale);

                    const ctx = elem.getContext('2d');
                    
                    ctx.drawImage(this.img, 0, 0, this.img.width, this.img.height, 0, 0, elem.width, elem.height);

                    ctx.canvas.toBlob((blob) => {
                        const file = new File([blob], 'test', {
                            type: this.form.format.extension
                        });
                        resolve(file);
                    }, this.form.format.extension, 1);
                }
            });
        },

        async uploadThumbnail(imgUrl, scale)
        {
            const thumbnail = await this.drawThumbnail(imgUrl, scale);
            console.log("thumbnail image: ", thumbnail);

            let formData = new FormData();
            formData.append('file', thumbnail);

            axios.post('/imgs/upload/thumb', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then( response => {
                    console.log("thumbnail image url: ", response.data.url);
                    
                    axios.patch('/api/imgs/' + this.form.image_id, { 'api_token': this.api_token, 'url': imgUrl, 'thumbnail_url': response.data.url, 'flag': "url_update" }) // Url update request
                            .then( resopnse => {
                                this.$router.push(resopnse.data.links.self);
                            })
                            .catch( error => {
                                console.log(error);
                            });
                })
                .catch( err => {
                    console.log(err);
                    console.log("failed");
                });
        },
        
        fileHandle()
        {
            if ( this.$refs.file.files[0].size > this.$maxSizePerEachItem * 1024 * 1024 )
            {
                document.getElementById('error_image').innerHTML = "Too large file size (Maximum: "+ this.$maxSizePerEachItem +"MB)";
                document.getElementById('image').value = '';
                return;
            }

            this.file = this.$refs.file.files[0];
            console.log("original image: ", this.file);

            if (this.file.type === 'image/jpeg' || this.file.type === 'image/png' || this.file.type === 'image/gif')
            {
                this.form.format.type = "photo";
                this.form.format.extension = this.file.type;
            }
            // else if (this.file.type === 'video/webm' || this.file.type === 'video/mp4')
            // {
            //     this.form.format.type = "webm";
            //     this.form.format.extension = this.file.type;
            // } // Demo only supports image file formats
            else
            {
                alert("Demo only supports image file formats");
                this.file = null;
                return;
            }

            this.fileLoaded = true;
        },

        submitForm()
        {
            if (this.fileLoaded == false)
            {
                document.getElementById('error_image').innerHTML = "Require at least 1 Image";
                return;
            }

            if (this.form.tags === "")
            {
                document.getElementById('error_tag').innerHTML = "Require at least 1 Tag";
                return;
            }

            let tags = this.form.tags.split(",");
            if (this.hasDuplicates(tags) == true)
            {
                document.getElementById('error_tag').innerHTML = "Inserted Tags aren't unique";
                return;
            }

            document.getElementById("submitButton").disabled = true;

            axios.post('/api/imgs', {...this.form, api_token: this.api_token}) // Image form request
                    .then( response => {

                        this.form.image_id = response.data.data.image_id;
                    
                        let formData = new FormData();
                        formData.append('file', this.file);

                        axios.post('/imgs/upload', formData, { headers: { 'Content-Type': 'multipart/form-data' } }) // Image upload request
                            .then( response => {
                                this.uploadThumbnail(response.data.url, 1/5);
                            })
                            .catch( errors => {
                                console.log(errors);
                                document.getElementById('error_image').innerHTML = errors.response.data.msg;
                                document.getElementById("submitButton").disabled = false;
                            });
                    })
                    .catch( errors => {
                        console.log(errors);
                        document.getElementById('error_tag').innerHTML = errors.response.data.msg;
                        document.getElementById("submitButton").disabled = false;
                    });
        }
    }
}
</script>

<style lang="">

</style>