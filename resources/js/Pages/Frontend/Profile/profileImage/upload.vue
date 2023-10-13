<template>
    <v-row no-gutters>
        <v-col col="12" sm="6">
            <div>
                <label>Upload image:</label>
                <v-file-input
                    v-model="image"
                    label="fileUpload"
                ></v-file-input>
                <v-btn 
                    :loading="loading"
                    class="flex-grow-1"
                    variant="tonal"
                    @click="load"
                >
                    Upload file
                </v-btn>
            </div>
            
        </v-col>
    </v-row>
    
</template>

<script>
    export default{
        data: () => ({
            image: false,
            loading: false,
        }),
        methods: {
            uploadFile(){
                const data = {
                    image: this.image,
                };
                axios.post('/profileImage/upload', data,
                {
                    headers: {
                        'Content-Type' : 'multipart/form-data'
                    }
                })
                .then((response) => {
                    return this.image.push(response.data) ;
                })
                .catch((response) => {
                    return alert('Error :' + response);
                })
                console.log(data);
            },
            load(){
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                    return this.uploadFile();
                }, 3000);
            }
        }
    }
</script>