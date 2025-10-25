import '../../css/front/material.css';


window.MaterialList = class extends Paginator {

    /**
     * Get And Paginate Materials of type
     *
     * @param {string} type Material type
     */
    constructor(type) {
        super({
            resultContainer: `#materialList`,
            moreButton: `#materialList_more`,
            actionURL: `/xhr/paginate/materials/list`,
        });

        this.conf.formData = new FormData;
        this.conf.formData.set(`_token`, global.csrfToken);
        this.conf.formData.set(`type`, type);

        this.fetchPaginatorResult();
    }
}

window.SubMaterialList = class extends Paginator {

    /**
     * Get And Paginate Sub Materials of Category
     *
     * @param {Object} conf Configuration
     * @param {string} conf.type Type of materials
     * @param {number|string} conf.categoryId Id of category
     * @param {HTMLElement|string} conf.resultContainer HTMLElement or selector of results container
     * @param {HTMLElement|string} conf.moreButton HTMLElement or selector of "more results" button
     */
    constructor(conf = {}) {
        super({
            resultContainer: conf.resultContainer,
            moreButton: conf.moreButton,
            actionURL: `/xhr/paginate/sub-materials/list`,
        })

        this.section = this.resultContainer.closest(`.materialList_section`);
        this.conf.formData = new FormData;
        this.conf.formData.set(`_token`, global.csrfToken);
        this.conf.formData.set(`type`, conf.type);
        this.conf.formData.set(`category_id`, String(conf.categoryId));

        this.fetchPaginatorResult();
    }

    afterFetchResult() {
        super.afterFetchResult();
        if (! this.resultItems.length) this.section.remove();
    }
}
