<template>
    <div
        class="bg-white text-black p-4 absolute pin-b pin-r transition-slow shadow-lg"
        style="width: 55.5rem; height: 87rem;  z-index: 2000000; "
        :style="containerStyle"
        v-click-outside="handleClickOutside"
    >
        <div ref="card" class="h-full bg-white">
            <div
                class="absolute pin-t pin-x"
                id="launch-builder"
                style="height: 7rem;"
                @click="toggle"
            ></div>

            <caps
                class="text-black self-center text-base w-full text-left absolute transition-slow pin-x pin-t pb-4"
                v-if="name.trim() === ''"
                :style="authorStyle"
                >Your Poem</caps
            >
            <caps
                class="text-black self-center text-base w-full text-left absolute transition-slow pin-x pin-t pb-4"
                v-else
                :style="authorStyle"
                >by {{ name }}</caps
            >

            <div class="flex flex-col">
                <div class="flex justify-center mb-16 mt-8 px-8">
                    <base-button @click="toggle">
                        <caps
                            v-if="title.trim() === ''"
                            class="text-5xl text-center transition tracking-wide"
                            :class="{ 'opacity-0': fadeInWordInfo }"
                            >{{ __("YOUR POEM") }}</caps
                        >
                        <caps
                            v-else
                            class="text-5xl text-center transition uppercase tracking-wide"
                            >{{ title }}</caps
                        >
                    </base-button>

                    <base-button
                        @click.prevent="toggle"
                        class="h-8 w-8 transition self-center"
                        :class="{ 'opacity-0': !isOpen }"
                    >
                        <close-icon class="w-full h-full" />
                    </base-button>
                </div>

                <div class="flex justify-end px-16">
                    <div
                        class="bg-white inline-block self-center w-full flex flex-col relative transition-slow"
                        style="width: 29rem"
                        :style="cardStyle"
                        id="card"
                    >
                        <div
                            class="border-b-2 relative"
                            :class="{
                                'border-white-30': fadeInWordInfo,
                                'border-black': !fadeInWordInfo
                            }"
                        >
                            <div class="absolute pin">
                                <div
                                    v-for="index in 16"
                                    :key="index"
                                    class="h-12 border-t-2 transition relative z-10"
                                    :class="{
                                        'border-white-30': fadeInWordInfo,
                                        'border-black': !fadeInWordInfo
                                    }"
                                ></div>
                            </div>

                            <div
                                v-for="index in 16"
                                :key="index"
                                class="h-12 row droppable transition relative z-10"
                                :class="{
                                    'border-white-30': fadeInWordInfo,
                                    'border-black': !fadeInWordInfo
                                }"
                                :id="`row-${index}`"
                            >
                                <word
                                    v-for="word in getRowWords(`row-${index}`)"
                                    :style="getWordStyle(word)"
                                    :id="word.id"
                                    :key="word.id"
                                    :word="word"
                                    class="no-clone z-100 absolute"
                                    :data-timestamp="word.timestamp"
                                    :data-word-id="word.id"
                                    :disable-animation="false"
                                />
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col justify-between ml-12 transition-slow"
                        style="width: 5rem"
                        :class="{ 'opacity-0': publishDrawer }"
                    >
                        <div class="flex flex-col">
                            <base-button
                                class="flex-col items-center"
                                @click="commonWordsDrawer = true"
                                id="common-words-button"
                            >
                                <div
                                    v-html="svg('add-circle', 'w-10 h-10 p-1')"
                                ></div>
                                <caps
                                    class="font-black text-2xs leading-normal mt-1 block"
                                >
                                    Add Common
                                    <br />Words
                                </caps>
                            </base-button>

                            <base-button
                                class="flex-col items-center mt-5"
                                v-click-outside="cancelClear"
                                @click="handleClearClick"
                            >
                                <div
                                    v-html="svg('trash', 'w-10 h-10 p-1')"
                                    :class="{ 'text-red': confirmClear }"
                                ></div>
                                <caps
                                    class="font-black text-2xs leading-normal mt-1 block"
                                    v-if="!confirmClear"
                                >
                                    Clear All
                                    <br />Words
                                </caps>
                                <caps
                                    class="text-red text-2xs leading-normal mt-1 block"
                                    v-if="confirmClear"
                                >
                                    Tap again
                                    <br />to Confirm
                                </caps>
                            </base-button>
                        </div>

                        <div
                            class="flex flex-col flex-grow justify-end"
                            id="drop-targets"
                        >
                            <drop-target
                                icon="duplicate"
                                :dragging="isDragging"
                                color="#1f9d55"
                                @drop="handleDuplicateDrop"
                            >
                                Drag to
                                <br />Duplicate
                            </drop-target>

                            <drop-target
                                icon="trash-x"
                                class="mt-3"
                                :dragging="isDragging"
                                color="#cc1f1a"
                                @drop="handleDeleteDrop"
                            >
                                Drag to
                                <br />Delete
                            </drop-target>

                            <drop-target
                                icon="eyeball"
                                class="mt-3"
                                :dragging="isDragging"
                                @drop="handleViewDrop"
                                color="#4151A3"
                            >
                                Drag to
                                <br />View Source
                            </drop-target>
                        </div>
                    </div>
                </div>

                <div class="justify-center flex">
                    <base-button
                        :disabled="wordsInCard.length === 0"
                        class="mt-12 bg-black rounded-full tracking-wide text-base font-black uppercase text-white px-10 py-4 transition-slow"
                        @click="publishDrawer = true"
                        :class="{
                            'opacity-25': wordsInCard.length === 0,
                            'opacity-0': publishDrawer
                        }"
                        >{{ __("Submit Poem") }}</base-button
                    >
                </div>
            </div>
        </div>
        <common-words
            :open="commonWordsDrawer"
            @close="commonWordsDrawer = false"
            @add="handleCommonWordsAdd"
        />

        <publish-poem
            :open="publishDrawer"
            @close="publishDrawer = false"
            v-on="$listeners"
            :key="publishKey"
        />
    </div>
</template>
<script>
import managesState from "../mixins/managesState";

import outlineHeading from "../components/OutlineHeading";
import wordInfoPopover from "../components/WordInfoPopover";
import word from "../components/Word";
import caps from "../components/Caps";
import baseButton from "../components/BaseButton";
import primaryButton from "../components/PrimiaryButton";
import trashIcon from "../components/TrashIcon";
import closeIcon from "../components/CloseIcon";
import plusIcon from "../components/PlusIcon";
import commonWords from "../components/CommonWords";
import publishPoem from "../components/PublishPoem";
import dropTarget from "../components/DropTarget";

import { mapMutations } from "vuex";

import __ from "../functions/translate";
import builder from "../store/builder.js";
import responses from "../store/responses.js";

import randomString from "../functions/randomString";
import multiply from "../functions/multiply";
import { draggable, droppable } from "../functions/jQueryUiHelpers.js";
import builderUtils from "../functions/builderUtils";
import domtoimage from "dom-to-image";

export default {
    mixins: [managesState],

    components: {
        dropTarget,
        publishPoem,
        commonWords,
        plusIcon,
        word,
        caps,
        baseButton,
        primaryButton,
        trashIcon,
        outlineHeading,
        wordInfoPopover,
        closeIcon
    },

    data() {
        return {
            commonWordsDrawer: false,
            publishDrawer: false,
            confirmClear: false,
            state: "default",
            activeRow: "my-words",
            popoverWord: undefined,
            draggingWord: undefined,
            isDragging: false,
            canClickOutside: false
        };
    },

    props: {
        open: Boolean
    },

    mounted() {
        this.setPlacingWord(false);
    },

    methods: {
        ...builder.mapMutations(["setPlacingWord", "setBlob"]),

        updateBlob() {
            return new Promise(async (resolve, reject) => {
                let base64 = await domtoimage.toPng(this.$refs.card);

                this.setBlob(base64);

                setTimeout(() => {
                    resolve();
                }, 1000);
            });
        },

        handleDuplicateDrop(e, ui) {
            let word = this.getWord(ui.draggable[0].id);

            this.duplicate(word);
        },
        handleDeleteDrop(e, ui) {
            let word = this.getWord(ui.draggable[0].id);

            this.deleteWord(word);
        },
        handleViewDrop(e, ui) {
            let word = this.getWord(ui.draggable[0].id);

            this.$emit("go-to-source", word.sourceId);
        },

        cancelClear() {
            this.confirmClear = false;
        },

        handleClickOutside(e) {
            if (this.open && this.canClickOutside) {
                this.toggle();
            }
        },

        handleClearClick() {
            if (this.confirmClear) {
                this.resetBuilder();
                this.confirmClear = false;
            } else {
                this.confirmClear = true;
            }
        },

        handleCommonWordsAdd(words) {
            words.forEach(w => this.placeWord(w));
        },

        toggle() {
            if (this.publishDrawer) {
                return;
            }

            this.$emit("toggle");

            if (!this.open) {
                this.commonWordsDrawer = false;
            }
        },
        __,

        ...builder.mapMutations([
            "setWordPosition",
            "resetBuilder",
            "deleteWord",
            "toggleWord"
        ]),

        ...builder.mapActions(["submitResponse", "refreshHelperWords"]),

        ...responses.mapMutations(["addResponse"]),

        clearLines() {
            this.words
                .filter(word => word.row !== "word-bank")
                .forEach(word => {
                    let isFromWordBank =
                        word.source === undefined ||
                        word.source.id === undefined;
                    let wordIsDuplicate = word.id.includes("dupe");

                    if (isFromWordBank || wordIsDuplicate) {
                        this.deleteWord(word);
                    } else {
                        this.setWordPosition({
                            wordId: word.id,
                            row: "my-words",
                            left: 0
                        });
                    }
                });
        },

        placeWord(word) {
            let wordsInCard = [...this.wordsInCard];

            if (this.dragging) {
                return;
            }

            if (wordsInCard.length === 0) {
                this.setWordPosition({
                    wordId: word.id,
                    row: "row-3",
                    left: multiply(80)
                });
            } else {
                let newPos = builderUtils.getNewWordPosition(wordsInCard.pop());
                this.setWordPosition({
                    ...newPos,
                    wordId: word.id
                });
            }
        },

        duplicate(word) {
            let newPos = builderUtils.getNewWordPosition(word);

            this.toggleWord({
                ...word,
                id: `${word.id}.dupe.${randomString()}`,
                row: newPos.row,
                left: newPos.left
            });

            this.closePopover();
        },

        removeFromCard(word) {
            let isFromWordBank = word.source.id === undefined;
            let wordIsDuplicate = word.id.includes("dupe");

            if (isFromWordBank || wordIsDuplicate) {
                this.deleteWord(word);
            } else {
                this.setWordPosition({
                    wordId: word.id,
                    row: "my-words",
                    left: 0
                });
            }
        },

        deletePopoverWord() {
            let isFromWordBank = this.popoverWord.source.id === undefined;
            let wordIsDuplicate = this.popoverWord.id.includes("dupe");

            if (isFromWordBank || wordIsDuplicate) {
                this.deleteWord(this.popoverWord);
            } else {
                this.setWordPosition({
                    wordId: this.popoverWord.id,
                    row: "my-words",
                    left: 0
                });
            }

            this.closePopover();
        },

        closePopover() {
            if (this.state === "default") {
                return;
            }

            this.state = "default";

            setTimeout(() => {
                this.popoverWord = undefined;
            }, 600);
        },
        getWordStyle(word) {
            let fadeWord =
                this.fadeInWordInfo && this.popoverWord.id !== word.id;

            return {
                left: `${word.left}px`,
                top: `0.23rem`,
                filter: fadeWord ? "greyscale(100%)" : "none",
                opacity: fadeWord ? 0.05 : 1
            };
        },
        showPopoverFor(e, word) {
            e.preventDefault();

            if (this.showingWordInfo || this.dragging) {
                return;
            }

            this.popoverWord = word;

            this.$nextTick(() => {
                this.state = "showWordInfo";
            });
        },
        back() {
            this.$router.go(-1);
        },

        toggleRow(row) {
            this.activeRow = row;
        },
        getButtonClass(row) {
            let isActive = this.activeRow === row;

            return {
                "border-transparent": !isActive,
                "border-white": isActive,
                "text-white-30": !isActive,
                "text-white": isActive
            };
        },
        getRowWords(row) {
            return this.words.filter(word => word.row === row.toString());
        },
        getLeft(rowEl, wordEl) {
            return Math.abs($(rowEl).offset().left - $(wordEl).offset().left);
        },
        handleDrop(e, ui) {
            let left = this.getLeft(e.target, ui.helper[0]);

            this.setWordPosition({
                wordId: ui.draggable[0].id,
                row: e.target.id,
                left
            });
        },
        getWord(id) {
            return this.words.find(item => item.id === id);
        },
        handleDrag(e, ui) {
            this.isDragging = true;
            let word = this.getWord(e.target.id);
            let rowId = word.row;

            $(".droppable").removeClass("z-50");
            $(`#${rowId}`).addClass("z-50");

            this.draggingWord = word;
        },
        handleStop(e, ui) {
            this.isDragging = false;

            $(`[id="${e.target.id}"]`).css({ top: ".13rem" });

            setTimeout(() => {
                this.draggingWord = undefined;
            }, 50);
        },
        initDragDrop() {
            let context = this;

            droppable(".droppable", {
                drop: this.handleDrop,
                accept: function(draggable) {
                    let word = context.getWord($(draggable).attr("id"));
                    let wordIsHelper = word === undefined || word.wordIsHelper;

                    return (
                        this.id !== "word-bank" &&
                        !(this.id === "my-words" && wordIsHelper)
                    );
                }
            });

            droppable("#delete", {
                drop: (e, ui) => {
                    let id = ui.draggable.first().attr("id");
                    let word = context.getWord(id);

                    this.removeFromCard(word);
                },
                accept: function(draggable) {
                    return true;
                }
            });

            draggable(".no-clone", {
                revert: function($dropTarget) {
                    return !$dropTarget || $dropTarget.hasClass("drop-target");
                },
                revertDuration: 0,
                start: this.handleDrag,
                stop: this.handleStop
            });
        }
    },
    computed: {
        ...builder.mapState(["placingWord", "publishKey"]),

        ...builder.mapState(["words", "name", "title"]),

        ...builder.mapGetters(["wordsInCard"]),

        cardStyle({ publishDrawer }) {
            if (publishDrawer) {
                return {
                    transform: "scale(1.3) translateY(6.2rem)"
                };
            }
        },

        authorStyle({ publishDrawer }) {
            if (publishDrawer) {
                return {
                    transform: `rotate(90deg) translateY(-154%) translateX(13.5rem) scale(1.3)`,
                    transformOrigin: "0 0"
                };
            }

            return {
                transform: `rotate(90deg) translateY(-100%) translateX(13.5rem)`,
                transformOrigin: "0 0"
            };
        },
        dragging({ draggingWord }) {
            return draggingWord !== undefined;
        },
        clearButtonClass({ wordsInCard, fadeInWordInfo }) {
            let wordLen = wordsInCard.length;

            return {
                "opacity-0": fadeInWordInfo || wordLen === 0
            };
        },
        showingWordBank({ activeRow }) {
            return activeRow === "word-bank";
        },
        showingWordInfo({ popoverWord }) {
            return popoverWord !== undefined;
        },
        fadeInWordInfo({ state }) {
            return state === "showWordInfo";
        },
        popoverStyle({ popoverWord }) {
            let $popover = $("#popover"),
                $card = $("#card"),
                $word = $(`[id="${popoverWord.id}"]`);

            let top = $word.offset().top - $card.offset().top;
            let left =
                $word.offset().left +
                $word.outerWidth() / 2 -
                $card.offset().left;

            let popoverHeight = $popover.outerHeight();
            let popoverWidth = $popover.outerWidth();

            return {
                opacity: this.fadeInWordInfo ? 1 : 0,
                "--category-color": popoverWord.source.category.color,
                top: top + "px",
                left: left + "px",
                "margin-left": `-${popoverWidth / 2}px`,
                "margin-top": `calc(-${popoverHeight / 2}px + 2.5rem)`,
                transition: "600ms opacity ease"
            };
        },
        step({ steps, currentStepIndex }) {
            return steps[currentStepIndex];
        },
        containerStyle({ open, placingWord }) {
            if (placingWord) {
                return {
                    transform: `translateY(68%)`,
                    transition: "300ms all ease"
                };
            }

            return {
                transform: `translateY(${open ? "0" : "85"}%)`
            };
        }
    },
    watch: {
        open() {
            if (!this.open) {
                this.publishDrawer = false;
            }

            clearTimeout(this.openTimeout);

            this.openTimeout = setTimeout(() => {
                this.canClickOutside = this.open;
            }, 300);
        },
        state: function() {
            if (this.fadeInWordInfo) {
                setTimeout(() => {
                    $(".row")
                        .off()
                        .click(() => {
                            this.closePopover();
                        });
                }, 300);
            } else {
                $(".row").off();
            }
        },
        words() {
            this.$nextTick(() => {
                this.$nextTick(() => {
                    this.initDragDrop();
                });
            });
        }
    },
    updated() {},
    mounted() {
        this.$nextTick(() => {
            this.initDragDrop();
        });

        setTimeout(() => {
            this.state = "init";
        }, 300);

        this.refreshHelperWords();
    }
};
</script>
<style lang="scss">
#delete.ui-droppable-hover {
    opacity: 1;
}
</style>
