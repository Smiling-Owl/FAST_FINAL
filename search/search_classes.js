document.addEventListener('DOMContentLoaded', function() {
    const classHeaders = document.querySelectorAll('.classes-list h3');
    const joinButtons = document.querySelectorAll('.join-button');
    const modal = document.getElementById('classDetailsModal');
    const modalCloseButtons = document.querySelectorAll('.close-button');
    const modalBodyContent = document.getElementById('modalBodyContent');
    const modalJoinButton = document.querySelector('.modal-join-button');


    let selectedClassId = null; // Track the currently selected class


    classHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const classId = this.dataset.classId;
            selectedClassId = classId; //store
            modalJoinButton.dataset.classId = classId; // Update modal's join button

            //show only the selected
            const targetDetails = document.querySelector(`.classes-list .class-details[data-class-id="${classId}"]`);

             // Display the details in the modal
            const className = this.textContent;
            const room = targetDetails.querySelector("strong:contains('Room:')").nextSibling.textContent;
            const time = targetDetails.querySelector("strong:contains('Time:')").nextSibling.textContent;
            const tutor = targetDetails.querySelector("strong:contains('Tutor:')").nextSibling.textContent;
            const capacity = targetDetails.querySelector("strong:contains('Capacity:')").nextSibling.textContent;
            const description = targetDetails.querySelector("p") ? targetDetails.querySelector("p").textContent : '';

            let detailsHTML = `<h2>${className}</h2>`;
            detailsHTML += `<p>${description}</p>`;
            detailsHTML += `<p><strong>Room:</strong> ${room}</p>`;
            detailsHTML += `<p><strong>Time:</strong> ${time}</p>`;
            detailsHTML += `<p><strong>Tutor:</strong> ${tutor}</p>`;
            detailsHTML += `<p><strong>Capacity:</strong> ${capacity}</p>`;
            modalBodyContent.innerHTML = detailsHTML;

            //show modal
            modal.style.display = "block";
        });
    });

    modalCloseButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.style.display = "none";
            modalBodyContent.innerHTML = "Loading..."; //reset
        });
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
          modal.style.display = "none";
          modalBodyContent.innerHTML = "Loading..."; //reset
        }
    });

    joinButtons.forEach(button => {
        button.addEventListener('click', function() {
            const classId = this.dataset.classId;
            selectedClassId = classId;
            handleJoinClass(classId);
        });
    });
    modalJoinButton.addEventListener('click', function() {
        const classId = selectedClassId;
        handleJoinClass(classId);
    });

    function handleJoinClass(classId){
        const studentId = document.body.dataset.studentId;

        if (!studentId) {
            alert('You must be logged in to request to join a class.');
            return;
        }

        fetch('request_join.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `class_id=${classId}&student_id=${studentId}`
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Request to join class sent successfully! Please wait for approval.');
                // Update button state.  Find the correct button, it could be in the modal or in the list.
                const listButton = document.querySelector(`.join-button[data-class-id="${classId}"]`);
                if(listButton){
                    listButton.parentNode.innerHTML = '<p class="request-status">Request Pending</p>';
                }
                 modal.style.display = "none";
                 modalBodyContent.innerHTML = "Loading...";
                 window.location.reload();

            } else if (data === 'full') {
                alert('This class is full.');
            } else if (data === 'already_joined') {
                alert('You have already requested/joined this class.');
            } else {
                alert('Error requesting to join the class: ' + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error processing your request.');
        });
    }

});

