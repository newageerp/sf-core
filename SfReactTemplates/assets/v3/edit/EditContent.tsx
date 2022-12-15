import React, { Fragment } from "react";
import { useHistory } from "react-router-dom";
import MainEdit from "../../v2/edit-forms/MainEdit";
import { showSuccessNotification } from "../navigation/NavigationComponent";
import { useNaePopup } from "../old-ui/OldPopupProvider";
import { useTemplatesLoader } from "@newageerp/v3.templates.templates-core";

interface Props {
  schema: string;
  type: string;
  id: string;

  defaultViewIndex?: number;

  isCompact?: boolean;

  parentElement?: any;
  newStateOptions?: any;

  requiredFields?: string[],
}

export default function EditContent(props: Props) {
  const history = useHistory();
  const { isPopup } = useNaePopup();
  const { data: tdata } = useTemplatesLoader();

  return (
    <Fragment>
      {/* {isPopup ? "POPUP" : "NOPOPUP"} */}
      <MainEdit
        key={"MvcEditRoutePageWoLayout-" + props.id + "-" + props.schema}
        schema={props.schema}
        type={props.type}
        id={props.id}
        onBack={tdata.onBack}
        newStateOptions={props.newStateOptions}
        requiredFields={props.requiredFields}
        onSave={(_el, backFunc) => {
          showSuccessNotification("Saved");
          if (tdata.onSaveCallback) {
            tdata.onSaveCallback(_el, backFunc);
          } else {
            if (isPopup) {
              backFunc();
            } else {
              const event = new CustomEvent("SFSOpenMainWindow", {
                detail: {
                  schema: props.schema,
                  type: "main",
                  id: _el.id,
                  replace: true,
                },
              });
              window.dispatchEvent(event);
            }
          }
        }}
      />
    </Fragment>
  );
}
