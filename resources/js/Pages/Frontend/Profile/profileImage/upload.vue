<template>
    <v-row fluid>
        <v-col class="d-flex justify-center flex-column" cols="auto" sm="12">
            <div>
                <label>image:</label>
                <v-file-input
                    v-model="image"
                    label="image"
                ></v-file-input>
                <v-btn 
                    :loading="loading"
                    class="flex-grow-1"
                    variant="text"
                    color="success"
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
                    this.$emit('close-dialog');
                    return this.image.push(response.data);
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