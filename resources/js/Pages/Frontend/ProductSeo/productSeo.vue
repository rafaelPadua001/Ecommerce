<template></template>

<script>
export default {
    props: ['selectProduct'],
    mounted(selectProduct) {
        axios.get(`/api/seo_product/${this.selectProduct.id}`)
            .then((response) => {
                let metaTags = response.data;
                const tag = document.createElement('meta');
                tag.setAttribute('name', metaTags.name || ''); // Use o operador de coalescência nula para evitar "undefined"
                tag.setAttribute('meta_name', metaTags.meta_name || ''); // Use o operador de coalescência nula para evitar "undefined"
                tag.setAttribute('keyword', metaTags.meta_keyword || ''); // Use o operador de coalescência nula para evitar "undefined"
                tag.setAttribute('content', metaTags.meta_description || ''); // Use o operador de coalescência nula para evitar "undefined"
                document.head.appendChild(tag);

            })
            .catch((response) => {
                alert('Error:' + response);
            });

    }
}
</script>