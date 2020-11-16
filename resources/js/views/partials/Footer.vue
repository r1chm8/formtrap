<template>
    <footer class="bg-grey-300 text-sm py-4">
        <div class="container text-center mx-auto px-8 md:flex md:items-center md:justify-between">
            <div>
                <!-- <ul class="list-reset footer-navigation mb-2 md:mb-0">
                    <li>
                        <a>Blog</a>
                    </li>

                    <li>
                        <a>FAQ</a>
                    </li>

                    <li>
                        <a>Privacy</a>
                    </li>

                    <li>
                        <a>Request Feature</a>
                    </li>
                </ul> -->
            </div>

            <div class="text-grey-600">
                &copy; 2019 FormTrap
            </div>
        </div>
    </footer>
</template>

<style lang="scss" scoped>
    .footer-navigation {
        display: inline-flex;

        > li {
            &:not(:last-child) {
                margin-right: 1rem;
            }

            > a {
                color: config('colors.grey-dark');
                font-weight: config('fontWeights.semibold');

                &:hover {
                    color: config('colors.grey-darker');
                }
            }
        }
    }
</style>
