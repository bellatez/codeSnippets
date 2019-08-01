//This is a javascript file used in an Online learning platform for rural areas

<script>
    $('#flash-overlay-modal').modal();

/*This section of the script is responsible for checking if a lesson video was stored on the internet or in the systems storage then decides on what to show the user in either case
*/

//Local video(video located on system or not available) if no video for that lesson has been saved on youtube and the user has gone through the lesson show an alert to the user that the lesson has been completed using the sweetalert package
//Provide the user the option to go to the next lesson from the alert box
@if($lesson->remote_video_link == null)
    document.querySelector('video').addEventListener('ended', function( evt ) {
        const url = "/lesson-details/{{$lesson->id}}/completed" 
		Swal.fire({
            title: 'Great Lesson Completed!',
            text: "You have completed lesson: {{$lesson->lesson_title}}",
            type: 'success',
            showCancelButton: false,
            confirmButtonColor: '#1EA9A4',
            confirmButtonText: 'Next Lesson!',
            preConfirm: (confirm) => {
                return fetch(url)
                .then(response => {
                    if (!response.ok) {
                    throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })
            },
            allowOutsideClick: () => false
            }).then((result) => {
            if (result.value) {
                // If there are no more lessons that have not been taken, display the alert dialog to tell the user that he has completed all the lessons in that trade
               if(result.value.data == null)
               {
                    Swal.fire({
                        title: "Congratulation!!",
                        text: "You have completed all lessons in this trade!",
                        type: 'success',
                        confirmButtonColor: '#1EA9A4',
                    }).then(function() {
                        location.reload();
                    });
               }else{
                location.href = "/lesson-details/"+result.value.data.id;
               } 
            }
        })
	});
@else
//youtube video
//In the case where a video has been provided , get the youtube link and pass to the Iframe which is like a youtube player
//When the video loads and is playing, check if it has ended. If the video has ended, show the alert dialog that the course has been completed
    var tag = document.createElement('script');
	tag.src = "https://www.youtube.com/iframe_api";
	var firstScript = document.getElementsByTagName('script')[0];
	firstScript.parentNode.insertBefore(tag, firstScript);

	function onYouTubeIframeAPIReady() {
		new YT.Player('player', {
			events: {
				'onStateChange': function(evt) {
					if (evt.data === YT.PlayerState.ENDED) {
						const url = "/lesson-details/{{$lesson->id}}/completed" 
                        Swal.fire({
                            title: 'Great Lesson Completed!',
                            text: "You have completed lesson: {{$lesson->lesson_title}}",
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#1EA9A4',
                            confirmButtonText: 'Next Lesson!',
                            preConfirm: (confirm) => {
                                return fetch(url)
                                .then(response => {
                                    if (!response.ok) {
                                    throw new Error(response.statusText)
                                    }
                                    return response.json()
                                })
                                .catch(error => {
                                    Swal.showValidationMessage(
                                    `Request failed: ${error}`
                                    )
                                })
                            },
                            allowOutsideClick: () => false
                            }).then((result) => {
                            if (result.value) {
                            if(result.value.data == null)
                            {
                                    Swal.fire({
                                        title: "Congratulation!!",
                                        text: "You have completed all lessons in this trade!",
                                        type: 'success',
                                        confirmButtonColor: '#1EA9A4',
                                    }).then(function() {
                                        location.reload();
                                    });
                            }else{
                                location.href = "/lesson-details/"+result.value.data.id;
                            } 
                            }
                        })
					}
				}
			}
		});
    }
@endif


// This section uses jquery to add functionality to a table precisely a dataTable which is a table package installed for more table functionality.
//the table can be customized like deciding if it should be responsive or not, collapsible has pagination
$(document).ready(function() {
    $('.dataTable').DataTable({
        processing: false,
        responsive: true,
        serverSide: false,
        "bDestroy": true,
        scrollCollapse: true,
        paging:         true,
        // order: [[8, 'desc'],[2,'asc']],
        "language": {
            "search": "@lang('Search'):",
            "lengthMenu":     "@lang('Show') _MENU_ @lang('entries')",
            "emptyTable":     "@lang('No data available in table')",
            "info":           "@lang('Showing') _START_ @lang('to') _END_ @lang('of') _TOTAL_ @lang('entries')",
            "infoEmpty":      "Showing 0 to 0 of 0 entries",
            "infoFiltered":   "(@lang('filtered') @lang('from') _MAX_ @lang('total entries'))",
            "loadingRecords": "@lang('Loading...')",
            "processing":     "@lang('Processing...')",
            "paginate": {
                "first":      "@lang('First')",
                "last":       "@lang('Last')",
                "next":       "@lang('Next')",
                "previous":   "@lang('Previous')"
            },
        },
    });
    // initializes a modal and stores data from the modal into database using ajax
        $('#approveCommentModal').on('show.bs.modal', function (event) {

            var editCommentBtn = $(event.relatedTarget) // Button that triggered the modal
            var url = editCommentBtn.data('url')
            var editCommentModal = $(this)
            editCommentModal.find('button[type="submit"]').attr('disabled',true);

            $.ajax({
                dataType  :'JSON',
                type      :'GET',
                url       : url,
                success   :function(response){
                    console.log(response)
                    if(response.status == 'success') {
                        editCommentModal.find('input[name="comment_id"]').val(response.data.id)

                        editCommentModal.find('button[type="submit"]').attr('disabled', false);

                    }
                    if(response.status =='error'){
                        toastr.warning(response.data,"@lang('Oops Something is not alright')");
                    }
                }
            });
        });
    });

    
// This section if for displaying the preview of the image you selected before you actually submit the form  to the database
// when you are filling a form and you have to insert an image, after you select the image, the image actually displays in the form so you can see what you selected before you submit

 var inputElement = document.getElementById("imgSelector");
    inputElement.addEventListener("change", readImage, false);
    function readImage() {
      var fileList = this.files; /* now you can work with the file list */

        if (fileList && fileList[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img_loc').attr('src', e.target.result);
            };

            reader.readAsDataURL(fileList[0]);
        }
    }
