import { UI, UIConfig } from "@newageerp/nae-react-ui";
import React, { Fragment } from "react";
import { useHistory } from "react-router-dom";
import MainEdit from "../../v2/edit-forms/MainEdit";
import { useTemplateLoader } from "../templates/TemplateLoader";

interface Props {
  schema: string;
  type: string;
  id: string;

  defaultViewIndex?: number;

  isCompact?: boolean;

  parentElement?: any;
  newStateOptions?: any;
}

export default function EditContent(props: Props) {
  const history = useHistory();
  const { isPopup } = UI.Popup.useNaePopup();
  const { data: tdata } = useTemplateLoader();

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
        onSave={(_el, backFunc) => {
          UIConfig.toast.success("Išsaugota");
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
