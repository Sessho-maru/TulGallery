let utils = {
    getImages(param, paginated, index, routeParams)
    {
        axios.get('/api/imgs')
                .then( response => {
                    let posts = response.data.data;
                    let pageSize = Math.ceil(posts.length / 24);

                    for (let i = 0; i < pageSize; i++)
                    {
                        paginated[i] = new Array();
                    }

                    let total = posts.length - 1;
                    let i = 0;
                    let j = 0;

                    for (total; total >= 0; total--)
                    {
                        paginated[i][j] = posts[total];

                        if (j % 23 == 0 && j != 0)
                        {
                            i++;
                            j = 0;
                        }
                        else
                        {
                            j++;
                        }
                    }

                    param["0"] = paginated[0];

                    // if (routeParams.currentPageIndex != null)
                    // {
                    //     let currentIndex = this.changeCurrentPage(routeParams.currentPageIndex, pageSize, true);
                    //     currentPage = paginated[currentIndex];
                    // }
                    // else
                    // {
                    //     let currentIndex = this.changeCurrentPage(index, pageSize);
                    //     currentPage = paginated[currentIndex];
                    // }
                })
                .catch( errors => {
                    alert("Unable to Fetch Images");
                });
    },

    changeCurrentPage(index, pageSize, viaBack = false)
    {   
        if (viaBack == false)
        {
            if (index < 0)
            {
                this.index++;
                return;
            }

            if (index >= pageSize)
            {
                this.index--;
                return;
            }

            console.log("current Page Index = " + index);
            return index;
        }
        else
        {
            return index;            
        }   
    }
}

export default utils