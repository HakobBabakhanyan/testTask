
const service = window.axios.create({
    baseURL: '/api/',
    withCredentials : true,
    timeout: 5000,
    // headers:{
    //     Authorization: `Bearer ${token}`
    // }
});

export default service;
