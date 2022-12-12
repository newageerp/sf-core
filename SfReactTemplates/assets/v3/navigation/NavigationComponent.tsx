import { OpenApi } from "@newageerp/nae-react-auth-wrapper";
import React, { Fragment, useEffect, useState } from "react";
import { useHistory } from "react-router";
import { Popup } from "@newageerp/v3.bundles.popup-bundle";
import { MailsForm } from "@newageerp/ui.ui-bundle";
import { useNaeWindow } from "../old-ui/OldNaeWindowProvider";
import SocketService from "../SocketService";

import {
  ConfirmationPopup,
  ConfirmationPopupProps,
} from "@newageerp/v3.bundles.popup-bundle";
import { FilesWindow } from "@newageerp/ui.ui-bundle";

export default function NavigationComponent() {
  const history = useHistory();
  const { showViewPopup, showEditPopup } = useNaeWindow();

  const [emailOptions, setEmailOptions] = useState<any>(undefined);
  const [previewFileOptions, setPreviewFileOptions] = useState<any>(undefined);

  const [confirmationProps, setConfirmationProps] =
    useState<ConfirmationPopupProps>();

  useEffect(() => {
    const eventListener = (e: any) => {
      const link = e.detail.link;
      history.push(link);
    };
    window.addEventListener("SFSOpenLink", eventListener);
    return () => {
      window.removeEventListener("SFSOpenLink", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : "main"
        }/view/${e.detail.id}`;
      if (e.detail.replace) {
        history.replace(link);
      } else {
        history.push(link);
      }
    };
    window.addEventListener("SFSOpenMainWindow", eventListener);
    return () => {
      window.removeEventListener("SFSOpenMainWindow", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      showViewPopup({
        id: e.detail.id,
        schema: e.detail.schema,
        type: e.detail.type ? e.detail.type : "main",
      });
    };
    window.addEventListener("SFSOpenModalWindow", eventListener);
    return () => {
      window.removeEventListener("SFSOpenModalWindow", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : "main"
        }/view/${e.detail.id}`;
      window.open(link, "_blank");
    };
    window.addEventListener("SFSOpenNewWindow", eventListener);
    return () => {
      window.removeEventListener("SFSOpenNewWindow", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : "main"
        }/edit/${e.detail.id}`;
      window.open(link, "_blank");
    };
    window.addEventListener("SFSOpenEditNewWindow", eventListener);
    return () => {
      window.removeEventListener("SFSOpenEditNewWindow", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : "main"
        }/edit/${e.detail.id}`;
      history.push(link, e.detail.options);
    };
    window.addEventListener("SFSOpenEditWindow", eventListener);
    return () => {
      window.removeEventListener("SFSOpenEditWindow", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      showEditPopup({
        id: e.detail.id,
        schema: e.detail.schema,
        type: e.detail.type ? e.detail.type : "main",
        newStateOptions: e.detail.options,
        onSaveCallback: e.detail.onSaveCallback,
        fieldsToReturnOnSave: e.detail.fieldsToReturnOnSave,
      });
    };
    window.addEventListener("SFSOpenEditModalWindow", eventListener);
    return () => {
      window.removeEventListener("SFSOpenEditModalWindow", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : "main"
        }/list`;
      history.push(link);
    };
    window.addEventListener("SFSOpenListWindow", eventListener);
    return () => {
      window.removeEventListener("SFSOpenListWindow", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      setEmailOptions(e.detail);
    };
    window.addEventListener("SFSOpenEmailForm", eventListener);
    return () => {
      window.removeEventListener("SFSOpenEmailForm", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      setPreviewFileOptions(e.detail);
    };
    window.addEventListener("SFSOpenFilePreview", eventListener);
    return () => {
      window.removeEventListener("SFSOpenFilePreview", eventListener);
    };
  }, []);

  useEffect(() => {
    const eventListener = (e: any) => {
      setConfirmationProps(e.detail);
    };
    window.addEventListener("SFSShowConfirmation", eventListener);
    return () => {
      window.removeEventListener("SFSShowConfirmation", eventListener);
    };
  }, []);

  return (
    <Fragment>
      {!!emailOptions && (
        <Popup
          isPopup={true}
          onClick={() => setEmailOptions(undefined)}
          title=""
          zIndex={450}
        >
          <MailsForm
            onBack={() => setEmailOptions(undefined)}
            {...emailOptions}
            onSend={() => {
              if (emailOptions.onSend) {
                emailOptions.onSend();
              }
              setEmailOptions(undefined);
            }}
          />
        </Popup>
      )}

      {!!previewFileOptions && (
        <Popup
          isPopup={true}
          onClick={() => setPreviewFileOptions(undefined)}
          title=""
          zIndex={450}
        >
          <FilesWindow
            onBack={() => setPreviewFileOptions(undefined)}
            {...previewFileOptions}
            inPopup={true}
          />
        </Popup>
      )}

      {!!confirmationProps && (
        <ConfirmationPopup
          {...confirmationProps}
          onClick={() => setConfirmationProps(undefined)}
          isPopup={true}
        />
      )}
    </Fragment>
  );
}

export const showTaskSentNotification = () => {
  OpenApi.toast.success(
    "Užduotis išsiųsta apdorojimui. Po jos įvykdymo gausite pranešimą."
  );
};

export const showSuccessNotification = (text: string) => {
  OpenApi.toast.success(text);
};

export const showErrorNotification = (text: string) => {
  OpenApi.toast.error(text);
};

export const SFSSocketService = SocketService;
